<?php
/**
 * GLIP
 * glip is a Git Library In PHP. It allows you to access bare git repositories
 * from PHP scripts, even without having git installed. The project's homepage is
 * located at <http://fimml.at/glip>.
 *
 * slightly modified for use with the open qoob framework.
 * added is_ascii function to attempt to detect if a blob is a string or binary file.
 * and archive function to zip/tar a tree of objects from a repo.
 *
 * released open-source under the GNU Lesser General Public License version 2
 * see <http://www.gnu.org/licenses/>.
 *
 * @author Patrik Fimml <patrik@fimml.at> https://github.com/patrikf/glip
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.03
 * @package qoob
 * @subpackage utils
 * @category version control
 */
//__________________________________________________________________________________
//                                                                              GLIP
/**
 * @relates Git
 * @brief Convert a SHA-1 hash from hexadecimal to binary representation.
 *
 * @param $hex (string) The hash in hexadecimal representation.
 * @return (string) The hash in binary representation.
 */
function sha1_bin($hex)
{
    return pack('H40', $hex);
}

/**
 * @relates Git
 * @brief Convert a SHA-1 hash from binary to hexadecimal representation.
 *
 * @param $bin (string) The hash in binary representation.
 * @return (string) The hash in hexadecimal representation.
 */
function sha1_hex($bin)
{
    return bin2hex($bin);
}

/**
 * @relates Git
 * test if blob data is ascii or binary.
 * this is hacky. please suggest alternatives.
 *
 * @param $blob
 * @return boolean
 */
function is_ascii($blob) {
    $test = addcslashes(substr($blob, 0, 1024), "\\\"'\0..\37\177..\377");
    $size = strlen(preg_replace('/[^\\\\]/', '', $test));
    return ($size < 200) ? true : false;
}
//__________________________________________________________________________________
//                                                                         git class
class Git
{
    public $dir;
    public $branches;

    const OBJ_NONE = 0;
    const OBJ_COMMIT = 1;
    const OBJ_TREE = 2;
    const OBJ_BLOB = 3;
    const OBJ_TAG = 4;
    const OBJ_OFS_DELTA = 6;
    const OBJ_REF_DELTA = 7;

    static public function getTypeID($name)
    {
	if ($name == 'commit')
	    return Git::OBJ_COMMIT;
	else if ($name == 'tree')
	    return Git::OBJ_TREE;
	else if ($name == 'blob')
	    return Git::OBJ_BLOB;
	else if ($name == 'tag')
	    return Git::OBJ_TAG;
	throw new Exception(sprintf('unknown type name: %s', $name), statusCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    static public function getTypeName($type)
    {
	if ($type == Git::OBJ_COMMIT)
	    return 'commit';
	else if ($type == Git::OBJ_TREE)
	    return 'tree';
	else if ($type == Git::OBJ_BLOB)
	    return 'blob';
	else if ($type == Git::OBJ_TAG)
	    return 'tag';
	throw new Exception(sprintf('no string representation of type %d', $type), statusCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function init($dir)
    {
        $this->dir = realpath($dir);
        if ($this->dir === FALSE || !@is_dir($this->dir))
            throw new Exception($dir." is not a directory", statusCodes::HTTP_INTERNAL_SERVER_ERROR);

		$this->packs = array();
		$dh = opendir(sprintf('%s'.SLASH.'objects'.SLASH.'pack', $this->dir));
        if ($dh !== FALSE) {
            while (($entry = readdir($dh)) !== FALSE)
                if (preg_match('#^pack-([0-9a-fA-F]{40})\.idx$#', $entry, $m))
                    $this->packs[] = sha1_bin($m[1]);
            closedir($dh);
        }
        $this->branches = array();
        $ddh = opendir(sprintf('%s'.SLASH.'refs'.SLASH.'heads', $this->dir));
        if ($ddh !== FALSE) {
            while (($entry = readdir($ddh)) !== FALSE) {
            	if($entry != "." && $entry != "..") {
            		$this->branches[] = $entry;
            	}
            }
            closedir($ddh);
        }
    }

    /**
     * @brief Tries to find $object_name in the fanout table in $f at $offset.
     *
     * @return array The range where the object can be located (first possible
     * location and past-the-end location)
     */
    protected function readFanout($f, $object_name, $offset)
    {
        if ($object_name{0} == "\x00")
        {
            $cur = 0;
            fseek($f, $offset);
            $after = Binary::fuint32($f);
        }
        else
        {
            fseek($f, $offset + (ord($object_name{0}) - 1)*4);
            $cur = Binary::fuint32($f);
            $after = Binary::fuint32($f);
        }

        return array($cur, $after);
    }

    /**
     * @brief Try to find an object in a pack.
     *
     * @param $object_name (string) name of the object (binary SHA1)
     * @return (array) an array consisting of the name of the pack (string) and
     * the byte offset inside it, or NULL if not found
     */
    protected function findPackedObject($object_name)
    {
        foreach ($this->packs as $pack_name)
        {
            $index = fopen(sprintf('%s'.SLASH.'objects'.SLASH.'pack'.SLASH.'pack-%s.idx', $this->dir, sha1_hex($pack_name)), 'rb');
            flock($index, LOCK_SH);

            /* check version */
            $magic = fread($index, 4);
            if ($magic != "\xFFtOc")
            {
                /* version 1 */
                /* read corresponding fanout entry */
                list($cur, $after) = $this->readFanout($index, $object_name, 0);

                $n = $after-$cur;
                if ($n == 0)
                    continue;

                /*
                 * TODO: do a binary search in [$offset, $offset+24*$n)
                 */
                fseek($index, 4*256 + 24*$cur);
                for ($i = 0; $i < $n; $i++)
                {
                    $off = Binary::fuint32($index);
                    $name = fread($index, 20);
                    if ($name == $object_name)
                    {
                        /* we found the object */
                        fclose($index);
                        return array($pack_name, $off);
                    }
                }
            }
            else
            {
                /* version 2+ */
                $version = Binary::fuint32($index);
                if ($version == 2)
                {
                    list($cur, $after) = $this->readFanout($index, $object_name, 8);

                    if ($cur == $after)
                        continue;

                    fseek($index, 8 + 4*255);
                    $total_objects = Binary::fuint32($index);

                    /* look up sha1 */
                    fseek($index, 8 + 4*256 + 20*$cur);
                    for ($i = $cur; $i < $after; $i++)
                    {
                        $name = fread($index, 20);
                        if ($name == $object_name)
                            break;
                    }
                    if ($i == $after)
                        continue;

                    fseek($index, 8 + 4*256 + 24*$total_objects + 4*$i);
                    $off = Binary::fuint32($index);
                    if ($off & 0x80000000)
                    {
                        /* packfile > 2 GB. Gee, you really want to handle this
                         * much data with PHP?
                         */
                        throw new Exception('64-bit packfiles offsets not implemented', statusCodes::HTTP_INTERNAL_SERVER_ERROR);
                    }

                    fclose($index);
                    return array($pack_name, $off);
                }
                else
                    throw new Exception('unsupported pack index format', statusCodes::HTTP_INTERNAL_SERVER_ERROR);
            }
            fclose($index);
        }
        /* not found */
        return NULL;
    }

    /**
     * @brief Apply the git delta $delta to the byte sequence $base.
     *
     * @param $delta (string) the delta to apply
     * @param $base (string) the sequence to patch
     * @return (string) the patched byte sequence
     */
    protected function applyDelta($delta, $base)
    {
        $pos = 0;

        $base_size = Binary::git_varint($delta, $pos);
        $result_size = Binary::git_varint($delta, $pos);

        $r = '';
        while ($pos < strlen($delta))
        {
            $opcode = ord($delta{$pos++});
            if ($opcode & 0x80)
            {
                /* copy a part of $base */
                $off = 0;
                if ($opcode & 0x01) $off = ord($delta{$pos++});
                if ($opcode & 0x02) $off |= ord($delta{$pos++}) <<  8;
                if ($opcode & 0x04) $off |= ord($delta{$pos++}) << 16;
                if ($opcode & 0x08) $off |= ord($delta{$pos++}) << 24;
                $len = 0;
                if ($opcode & 0x10) $len = ord($delta{$pos++});
                if ($opcode & 0x20) $len |= ord($delta{$pos++}) <<  8;
                if ($opcode & 0x40) $len |= ord($delta{$pos++}) << 16;
                if ($len == 0) $len = 0x10000;
                $r .= substr($base, $off, $len);
            }
            else
            {
                /* take the next $opcode bytes as they are */
                $r .= substr($delta, $pos, $opcode);
                $pos += $opcode;
            }
        }
        return $r;
    }

    /**
     * @brief Unpack an object from a pack.
     *
     * @param $pack (resource) open .pack file
     * @param $object_offset (integer) offset of the object in the pack
     * @return (array) an array consisting of the object type (int) and the
     * binary representation of the object (string)
     */
    protected function unpackObject($pack, $object_offset)
    {
        fseek($pack, $object_offset);

        /* read object header */
        $c = ord(fgetc($pack));
        $type = ($c >> 4) & 0x07;
        $size = $c & 0x0F;
        for ($i = 4; $c & 0x80; $i += 7)
        {
            $c = ord(fgetc($pack));
            $size |= (($c & 0x7F) << $i);
        }

        /* compare sha1_file.c:1608 unpack_entry */
        if ($type == Git::OBJ_COMMIT || $type == Git::OBJ_TREE || $type == Git::OBJ_BLOB || $type == Git::OBJ_TAG)
        {
            /*
             * We don't know the actual size of the compressed
             * data, so we'll assume it's less than
             * $object_size+512.
             *
             * FIXME use PHP stream filter API as soon as it behaves
             * consistently
             */
            $data = gzuncompress(fread($pack, $size+512), $size);
        }
        else if ($type == Git::OBJ_OFS_DELTA)
        {
            /* 20 = maximum varint length for offset */
            $buf = fread($pack, $size+512+20);

            /*
             * contrary to varints in other places, this one is big endian
             * (and 1 is added each turn)
             * see sha1_file.c (get_delta_base)
             */
            $pos = 0;
            $offset = -1;
            do
            {
                $offset++;
                $c = ord($buf{$pos++});
                $offset = ($offset << 7) + ($c & 0x7F);
            }
            while ($c & 0x80);

            $delta = gzuncompress(substr($buf, $pos), $size);
            unset($buf);

            $base_offset = $object_offset - $offset;
            assert($base_offset >= 0);
            list($type, $base) = $this->unpackObject($pack, $base_offset);

            $data = $this->applyDelta($delta, $base);
        }
        else if ($type == Git::OBJ_REF_DELTA)
        {
            $base_name = fread($pack, 20);
            list($type, $base) = $this->getRawObject($base_name);

            // $size is the length of the uncompressed delta
            $delta = gzuncompress(fread($pack, $size+512), $size);

            $data = $this->applyDelta($delta, $base);
        }
        else
            throw new Exception(sprintf('object of unknown type %d', $type), statusCodes::HTTP_INTERNAL_SERVER_ERROR);

        return array($type, $data);
    }

    /**
     * @brief Fetch an object in its binary representation by name.
     *
     * Throws an exception if the object cannot be found.
     *
     * @param $object_name (string) name of the object (binary SHA1)
     * @return (array) an array consisting of the object type (int) and the
     * binary representation of the object (string)
     */
    protected function getRawObject($object_name)
    {
        static $cache = array();
        /* FIXME allow limiting the cache to a certain size */

        if (isset($cache[$object_name]))
            return $cache[$object_name];
	$sha1 = sha1_hex($object_name);
	$path = sprintf('%s'.SLASH.'objects'.SLASH.'%s'.SLASH.'%s', $this->dir, substr($sha1, 0, 2), substr($sha1, 2));
	if (file_exists($path))
	{

            list($hdr, $object_data) = explode("\0", gzuncompress(file_get_contents($path)), 2);

            sscanf($hdr, "%s %d", $type, $object_size);
            $object_type = Git::getTypeID($type);
            $r = array($object_type, $object_data);
	}
	else if ($x = $this->findPackedObject($object_name))
	{
            list($pack_name, $object_offset) = $x;

            $pack = fopen(sprintf('%s'.SLASH.'objects'.SLASH.'pack'.SLASH.'pack-%s.pack', $this->dir, sha1_hex($pack_name)), 'rb');
            flock($pack, LOCK_SH);

            /* check magic and version */
            $magic = fread($pack, 4);
            $version = Binary::fuint32($pack);
            if ($magic != 'PACK' || $version != 2)
                throw new Exception('unsupported pack format', statusCodes::HTTP_INTERNAL_SERVER_ERROR);

            $r = $this->unpackObject($pack, $object_offset);
            fclose($pack);
	}
        else
            throw new Exception(sprintf('object not found: %s', sha1_hex($object_name)), statusCodes::HTTP_INTERNAL_SERVER_ERROR);
        $cache[$object_name] = $r;
        return $r;
    }

    /**
     * @brief Fetch an object in its PHP representation.
     *
     * @param $name (string) name of the object (binary SHA1)
     * @return (GitObject) the object
     */
    public function getObject($name)
    {
	list($type, $data) = $this->getRawObject($name);
	$object = GitObject::create($this, $type);
	$object->unserialize($data);
	assert($name == $object->getName());
	return $object;
    }

    /**
     * @brief Look up a branch.
     *
     * @param $branch (string) The branch to look up, defaulting to @em master.
     * @return (string) The tip of the branch (binary sha1).
     */
    public function getTip($branch='master')
    {
	$subpath = sprintf('refs'.SLASH.'heads'.SLASH.'%s', $branch);
	$path = sprintf('%s'.SLASH.'%s', $this->dir, $subpath);
	if (file_exists($path))
	    return sha1_bin(file_get_contents($path));
	$path = sprintf('%s'.SLASH.'packed-refs', $this->dir);
	if (file_exists($path))
	{
	    $head = NULL;
	    $f = fopen($path, 'rb');
	    flock($f, LOCK_SH);
	    while ($head === NULL && ($line = fgets($f)) !== FALSE)
	    {
		if ($line{0} == '#')
		    continue;
		$parts = explode(' ', trim($line));
		if (count($parts) == 2 && $parts[1] == $subpath)
		    $head = sha1_bin($parts[0]);
	    }
	    fclose($f);
	    if ($head !== NULL)
		return $head;
	}
	throw new Exception(sprintf('no such branch: %s', $branch), statusCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @brief Compress and export a Git tree 
     *
     * @param $file (string) the export filename w/o extension
     * @param $repo (string) the local directory of the repository
     * @param $tree (string) the SHA1 hash of the tree to export
     * @param $compression (string) the compression type 'tar' or 'zip'
     * @return (string) The tip of the branch (binary sha1).
     */    
    function archive($file, $repo, $tree, $compression = 'zip') 
    {
        if($compression == 'tar') {
            $mime = 'x-tar-gz';
            $ext = 'tar.gz';
            $cmd =  "git --git-dir=".escapeshellarg($repo)." archive --format=tar ".escapeshellarg($tree)." |gzip"; 
        } else {
            $mime = 'x-zip';
            $ext = 'zip';
            $cmd =  "git --git-dir=".escapeshellarg($repo)." archive --format=zip ".escapeshellarg($tree);
        }
        header("Content-Type: application/$mime");
        header("Content-Transfer-Encoding: binary");
        header("Content-Disposition: attachment; filename=\"$file.$ext\";");
        $result = 0;
        passthru($cmd, $result);
        return $result;
    }
}
//__________________________________________________________________________________
//                                                                        git object
class GitObject
{
    /**
     * @brief (Git) The repository this object belongs to.
     */
    public $repo;
    public $type;
    public $name = NULL;

    /**
     * @brief Get the object's cached SHA-1 hash value.
     *
     * @return (string) The hash value (binary sha1).
     */
    public function getName() {	return $this->name; }

    /**
     * @brief Get the object's type.
     *
     * @return (integer) One of Git::OBJ_COMMIT, Git::OBJ_TREE or
     * GIT::OBJ_BLOB.
     */
    public function getType() { return $this->type; }

    /**
     * @brief Create a GitObject of the specified type.
     *
     * @param $repo (Git) The repository the object belongs to.
     * @param $type (integer) Object type (one of Git::OBJ_COMMIT, Git::OBJ_TREE, Git::OBJ_BLOB).
     * @return A new GitCommit, GitTree or GitBlob object respectively.
     */
    static public function create($repo, $type)
    {
	if ($type == Git::OBJ_COMMIT)
	    return new GitCommit($repo);
	if ($type == Git::OBJ_TREE)
	    return new GitTree($repo);
	if ($type == Git::OBJ_BLOB)
	    return new GitBlob($repo);
	throw new Exception(sprintf('unhandled object type %d', $type), statusCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @brief Internal function to calculate the hash value of a git object of the
     * current type with content $data.
     *
     * @param $data (string) The data to hash.
     * @return (string) The hash value (binary sha1).
     */
    protected function hash($data)
    {
	$hash = hash_init('sha1');
	hash_update($hash, Git::getTypeName($this->type));
	hash_update($hash, ' ');
	hash_update($hash, strlen($data));
	hash_update($hash, "\0");
	hash_update($hash, $data);
	return hash_final($hash, TRUE);
    }

    /**
     * @brief Internal constructor for use from derived classes.
     *
     * Never use this function except from a derived class. Use the
     * constructor of a derived class, create() or Git::getObject() instead.
     */
    public function __construct($repo, $type)
    {
	$this->repo = $repo;
	$this->type = $type;
    }

    /**
     * @brief Populate this object with values from its string representation.
     *
     * Note that the types of $this and the serialized object in $data have to
     * match.
     *
     * @param $data (string) The serialized representation of an object, as
     * it would be stored by git.
     */
    public function unserialize($data)
    {
	$this->name = $this->hash($data);
	$this->_unserialize($data);
    }

    /**
     * @brief Get the string representation of an object.
     *
     * @return The serialized representation of the object, as it would be
     * stored by git.
     */
    public function serialize()
    {
	return $this->_serialize();
    }

    /**
     * @brief Update the SHA-1 name of an object.
     *
     * You need to call this function after making changes to attributes in
     * order to have getName() return the correct hash.
     */
    public function rehash()
    {
	$this->name = $this->hash($this->serialize());
    }

    /**
     * @brief Write this object in its serialized form to the git repository
     * given at creation time.
     */
    public function write()
    {
	$sha1 = sha1_hex($this->name);
	$path = sprintf('%s'.SLASH.'objects'.SLASH.'%s'.SLASH.'%s', $this->repo->dir, substr($sha1, 0, 2), substr($sha1, 2));
	if (file_exists($path))
	    return FALSE;
	$dir = dirname($path);
	if (!is_dir($dir))
	    mkdir(dirname($path), 0770);
	$f = fopen($path, 'ab');
	flock($f, LOCK_EX);
	ftruncate($f, 0);
	$data = $this->serialize();
	$data = Git::getTypeName($this->type).' '.strlen($data)."\0".$data;
	fwrite($f, gzcompress($data));
	fclose($f);
	return TRUE;
    }
}
//__________________________________________________________________________________
//                                                                        git commit

class GitCommit extends GitObject
{
    /**
     * @brief (string) The tree referenced by this commit, as binary sha1
     * string.
     */
    public $tree;

    /**
     * @brief (array of string) Parent commits of this commit, as binary sha1
     * strings.
     */
    public $parents;

    /**
     * @brief (GitCommitStamp) The author of this commit.
     */
    public $author;

    /**
     * @brief (GitCommitStamp) The committer of this commit.
     */
    public $committer;

    /**
     * @brief (string) Commit summary, i.e. the first line of the commit message.
     */
    public $summary;

    /**
     * @brief (string) Everything after the first line of the commit message.
     */
    public $detail;

    public function __construct($repo)
    {
	parent::__construct($repo, Git::OBJ_COMMIT);
    }

    public function _unserialize($data)
    {
	$lines = explode("\n", $data);
	unset($data);
	$meta = array('parent' => array());
	while (($line = array_shift($lines)) != '')
	{
	    $parts = explode(' ', $line, 2);
	    if (!isset($meta[$parts[0]]))
		$meta[$parts[0]] = array($parts[1]);
	    else
		$meta[$parts[0]][] = $parts[1];
	}

	$this->tree = sha1_bin($meta['tree'][0]);
	$this->parents = array_map('sha1_bin', $meta['parent']);
	$this->author = new GitCommitStamp;
	$this->author->unserialize($meta['author'][0]);
	$this->committer = new GitCommitStamp;
	$this->committer->unserialize($meta['committer'][0]);

	$this->summary = array_shift($lines);
	$this->detail = implode("\n", $lines);

        $this->history = NULL;
    }

    public function _serialize()
    {
	$s = '';
	$s .= sprintf("tree %s\n", sha1_hex($this->tree));
	foreach ($this->parents as $parent)
	    $s .= sprintf("parent %s\n", sha1_hex($parent));
	$s .= sprintf("author %s\n", $this->author->serialize());
	$s .= sprintf("committer %s\n", $this->committer->serialize());
	$s .= "\n".$this->summary."\n".$this->detail;
	return $s;
    }

    /**
     * @brief Get commit history in topological order.
     *
     * @return (array of GitCommit)
     */
    public function getHistory()
    {
        if ($this->history)
            return $this->history;

        /* count incoming edges */
        $inc = array();

        $queue = array($this);
        while (($commit = array_shift($queue)) !== NULL)
        {
            foreach ($commit->parents as $parent)
            {
                if (!isset($inc[$parent]))
                {
                    $inc[$parent] = 1;
                    $queue[] = $this->repo->getObject($parent);
                }
                else
                    $inc[$parent]++;
            }
        }

        $queue = array($this);
        $r = array();
        while (($commit = array_pop($queue)) !== NULL)
        {
            array_unshift($r, $commit);
            foreach ($commit->parents as $parent)
            {
                if (--$inc[$parent] == 0)
                    $queue[] = $this->repo->getObject($parent);
            }
        }

        $this->history = $r;
        return $r;
    }

    /**
     * @brief Get the tree referenced by this commit.
     *
     * @return The GitTree referenced by this commit.
     */
    public function getTree()
    {
        return $this->repo->getObject($this->tree);
    }

    /**
     * @copybrief GitTree::find()
     *
     * This is a convenience function calling GitTree::find() on the commit's
     * tree.
     *
     * @copydetails GitTree::find()
     */
    public function find($path)
    {
        return $this->getTree()->find($path);
    }

    static public function treeDiff($a, $b)
    {
        return GitTree::treeDiff($a ? $a->getTree() : NULL, $b ? $b->getTree() : NULL);
    }
}
//__________________________________________________________________________________
//                                                                  git commit stamp
class GitCommitStamp
{
    public $name;
    public $email;
    public $time;
    public $offset;

    public function unserialize($data)
    {
	assert(preg_match('/^(.+?)\s+<(.+?)>\s+(\d+)\s+([+-]\d{4})$/', $data, $m));
	$this->name = $m[1];
	$this->email = $m[2];
	$this->time = intval($m[3]);
	$off = intval($m[4]);
	$this->offset = ($off/100) * 3600 + ($off%100) * 60;
    }

    public function serialize()
    {
	if ($this->offset%60)
	    throw new Exception('cannot serialize sub-minute timezone offset', statusCodes::HTTP_INTERNAL_SERVER_ERROR);
	return sprintf('%s <%s> %d %+05d', $this->name, $this->email, $this->time, ($this->offset/3600)*100 + ($this->offset/60)%60);
    }
}
//__________________________________________________________________________________
//                                                                          git tree
class GitTreeError extends Exception {}
class GitTreeInvalidPathError extends GitTreeError {}

class GitTree extends GitObject
{
    public $nodes = array();

    public function __construct($repo)
    {
	parent::__construct($repo, Git::OBJ_TREE);
    }

    public function _unserialize($data)
    {
	$this->nodes = array();
	$start = 0;
	while ($start < strlen($data))
	{
	    $node = new stdClass;

	    $pos = strpos($data, "\0", $start);
	    list($node->mode, $node->name) = explode(' ', substr($data, $start, $pos-$start), 2);
	    $node->mode = intval($node->mode, 8);
            $node->is_dir = !!($node->mode & 040000);
            $node->is_submodule = ($node->mode == 57344);
	    $node->object = substr($data, $pos+1, 20);
	    $start = $pos+21;

	    $this->nodes[$node->name] = $node;
	}
	unset($data);
    }

    protected static function nodecmp(&$a, &$b)
    {
        return strcmp($a->name, $b->name);
    }

    public function _serialize()
    {
	$s = '';
        /* git requires nodes to be sorted */
        uasort($this->nodes, array('GitTree', 'nodecmp'));
	foreach ($this->nodes as $node)
	    $s .= sprintf("%s %s\0%s", base_convert($node->mode, 10, 8), $node->name, $node->object);
	return $s;
    }

    /**
     * @brief Find the tree or blob at a certain path.
     *
     * @throws GitTreeInvalidPathError The path was found to be invalid. This
     * can happen if you are trying to treat a file like a directory (i.e.
     * @em foo/bar where @em foo is a file).
     *
     * @param $path (string) The path to look for, relative to this tree.
     * @return The GitTree or GitBlob at the specified path, or NULL if none
     * could be found.
     */
    public function find($path)
    {
        if (!is_array($path))
            $path = explode('/', $path);

        while ($path && !$path[0])
            array_shift($path);
        if (!$path)
            return $this->getName();

        if (!isset($this->nodes[$path[0]]))
            return NULL;
        $cur = $this->nodes[$path[0]]->object;

        array_shift($path);
        while ($path && !$path[0])
            array_shift($path);

        if (!$path)
            return $cur;
        else
        {
            $cur = $this->repo->getObject($cur);
            if (!($cur instanceof GitTree))
                throw new GitTreeInvalidPathError;
            return $cur->find($path);
        }
    }

    /**
     * @brief Recursively list the contents of a tree.
     *
     * @return (array mapping string to string) An array where the keys are
     * paths relative to the current tree, and the values are SHA-1 names of
     * the corresponding blobs in binary representation.
     */
    public function listRecursive()
    {
        $r = array();

        foreach ($this->nodes as $node)
        {
            if ($node->is_dir)
            {
                if ($node->is_submodule)
                {
                    $r[$node->name. ':submodule'] = $node->object;
                }
                else
                {
                    $subtree = $this->repo->getObject($node->object);
                    foreach ($subtree->listRecursive() as $entry => $blob)
                    {
                        $r[$node->name . '/' . $entry] = $blob;
                    }
                }
            }
            else
            {
                $r[$node->name] = $node->object;
            }
        }

        return $r;
    }

    /**
     * @brief Updates a node in this tree.
     *
     * Missing directories in the path will be created automatically.
     *
     * @param $path (string) Path to the node, relative to this tree.
     * @param $mode Git mode to set the node to. 0 if the node shall be
     * cleared, i.e. the tree or blob shall be removed from this path.
     * @param $object (string) Binary SHA-1 hash of the object that shall be
     * placed at the given path.
     *
     * @return (array of GitObject) An array of GitObject%s that were newly
     * created while updating the specified node. Those need to be written to
     * the repository together with the modified tree.
     */
    public function updateNode($path, $mode, $object)
    {
        if (!is_array($path))
            $path = explode('/', $path);
        $name = array_shift($path);
        if (count($path) == 0)
        {
            /* create leaf node */
            if ($mode)
            {
                $node = new stdClass;
                $node->mode = $mode;
                $node->name = $name;
                $node->object = $object;
                $node->is_dir = !!($mode & 040000);

                $this->nodes[$node->name] = $node;
            }
            else
                unset($this->nodes[$name]);

            return array();
        }
        else
        {
            /* descend one level */
            if (isset($this->nodes[$name]))
            {
                $node = $this->nodes[$name];
                if (!$node->is_dir)
                    throw new GitTreeInvalidPathError;
                $subtree = clone $this->repo->getObject($node->object);
            }
            else
            {
                /* create new tree */
                $subtree = new GitTree($this->repo);

                $node = new stdClass;
                $node->mode = 040000;
                $node->name = $name;
                $node->is_dir = TRUE;

                $this->nodes[$node->name] = $node;
            }
            $pending = $subtree->updateNode($path, $mode, $object);

            $subtree->rehash();
            $node->object = $subtree->getName();

            $pending[] = $subtree;
            return $pending;
        }
    }

    const TREEDIFF_A = 0x01;
    const TREEDIFF_B = 0x02;

    const TREEDIFF_REMOVED = self::TREEDIFF_A;
    const TREEDIFF_ADDED = self::TREEDIFF_B;
    const TREEDIFF_CHANGED = 0x03;

    static public function treeDiff($a_tree, $b_tree)
    {
        $a_blobs = $a_tree ? $a_tree->listRecursive() : array();
        $b_blobs = $b_tree ? $b_tree->listRecursive() : array();

        $a_files = array_keys($a_blobs);
        $b_files = array_keys($b_blobs);

        $changes = array();

        sort($a_files);
        sort($b_files);
        $a = $b = 0;
        while ($a < count($a_files) || $b < count($b_files))
        {
            if ($a < count($a_files) && $b < count($b_files))
                $cmp = strcmp($a_files[$a], $b_files[$b]);
            else
                $cmp = 0;
            if ($b >= count($b_files) || $cmp < 0)
            {
                $changes[$a_files[$a]] = self::TREEDIFF_REMOVED;
                $a++;
            }
            else if ($a >= count($a_files) || $cmp > 0)
            {
                $changes[$b_files[$b]] = self::TREEDIFF_ADDED;
                $b++;
            }
            else
            {
                if ($a_blobs[$a_files[$a]] != $b_blobs[$b_files[$b]])
                    $changes[$a_files[$a]] = self::TREEDIFF_CHANGED;

                $a++;
                $b++;
            }
        }

        return $changes;
    }
}
//__________________________________________________________________________________
//                                                                          git blob
class GitBlob extends GitObject
{
    /**
     * @brief The data contained in this blob.
     */
    public $data = NULL;

    public function __construct($repo)
    {
	parent::__construct($repo, Git::OBJ_BLOB);
    }

    public function _unserialize($data)
    {
	$this->data = $data;
    }

    public function _serialize()
    {
	return $this->data;
    }
}
//__________________________________________________________________________________
//                                                                      binary class
final class Binary
{
    static public function uint16($str, $pos=0)
    {
        return ord($str{$pos+0}) << 8 | ord($str{$pos+1});
    }

    static public function uint32($str, $pos=0)
    {
        $a = unpack('Nx', substr($str, $pos, 4));
        return $a['x'];
    }

    static public function nuint32($n, $str, $pos=0)
    {
        $r = array();
        for ($i = 0; $i < $n; $i++, $pos += 4)
            $r[] = Binary::uint32($str, $pos);
        return $r;
    }

    static public function fuint32($f) { return Binary::uint32(fread($f, 4)); }
    static public function nfuint32($n, $f) { return Binary::nuint32($n, fread($f, 4*$n)); }

    static public function git_varint($str, &$pos=0)
    {
        $r = 0;
        $c = 0x80;
        for ($i = 0; $c & 0x80; $i += 7)
        {
            $c = ord($str{$pos++});
            $r |= (($c & 0x7F) << $i);
        }
        return $r;
    }
}

?>