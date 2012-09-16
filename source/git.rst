GIT Utility
***********

.. php:function:: sha1_bin()

   :relates: Git

   :brief: Convert a SHA-1 hash from hexadecimal to binary representation.
   :param $hex $string): The hash in hexadecimal representation.

   :returns: (string) $he hash in binary representation.

.. php:function:: sha1_hex()

   :relates: Git

   :brief: Convert a SHA-1 hash from binary to hexadecimal representation.
   :param $bin $string): The hash in binary representation.

   :returns: (string) the hash in hexadecimal representation.

.. php:function:: is_ascii()

   test if blob data is ascii or binary.
   

   :relates: Git
   :param $blob:

   :returns: boolean

.. php:class:: Git

      :author: Patrik Fimml <patrik@fimml.at> https://github.com/patrikf/glip

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.03

      :package: qoob

      :subpackage: utils

      :category: version control

   .. php:attr:: $dir

   .. php:attr:: $branches

   .. php:const:: Git:: OBJ_NONE = 0;

   .. php:const:: Git:: OBJ_COMMIT = 1;

   .. php:const:: Git:: OBJ_TREE = 2;

   .. php:const:: Git:: OBJ_BLOB = 3;

   .. php:const:: Git:: OBJ_TAG = 4;

   .. php:const:: Git:: OBJ_OFS_DELTA = 6;

   .. php:const:: Git:: OBJ_REF_DELTA = 7;

   .. php:method:: Git::getTypeID()

   .. php:method:: Git::getTypeName()

   .. php:method:: Git::init()

   .. php:method:: Git::readFanout()

      
      :brief: Tries to find $object_name in the fanout table in if at $offset.

      :returns: array the range where the object can be located (first possible)

   .. php:method:: Git::findPackedObject()

      
      the byte offset inside it, or NULL if not found

      :brief: Try to find an object in a pack.
      :param $object_name $string): name of the object (binary SHA1)

      :returns: (array) an array consisting of the name of the pack (string) and

   .. php:method:: Git::applyDelta()

      :brief: Apply the git delta $delta to the byte sequence $base.
      :param $delta $string): the delta to apply
      :param $base $string): the sequence to patch

      :returns: (string) the patched byte sequence

   .. php:method:: Git::unpackObject()

      
      binary representation of the object (string)

      :brief: Unpack an object from a pack.
      :param $pack $resource): open .pack file
      :param $object_offset $integer): offset of the object in the pack

      :returns: (array) an array consisting of the object data and type

   .. php:method:: Git::getRawObject()

      
      Throws an exception if the object cannot be found.

      binary representation of the object (string)

      :brief: Fetch an object in its binary representation by name.
      :param $object_name $string): name of the object (binary SHA1)

      :returns: (array) an array consisting of the object type (int) and the

   .. php:method:: Git::getObject()

      :brief: Fetch an object in its PHP representation.
      :param $name $string): name of the object (binary SHA1)

      :returns: (GitObject) the object

   .. php:method:: Git::getTip()

      :brief: Look up a branch.
      :param $branch $string): The branch to look up, defaults to master.

      :returns: (string) the tip of the branch (binary sha1).

   .. php:method:: Git::archive()

      :brief: Compress and export a Git tree
      :param $file $string): the export filename w/o extension
      :param $repo $string): the local directory of the repository
      :param $tree $string): the SHA1 hash of the tree to export
      :param $compression $string): the compression type 'tar' or 'zip'

      :returns: (string) the tip of the branch (binary sha1).

.. php:class:: GitObject

   .. php:attr:: $repo

      :brief: (Git) the repository this object belongs to.

   .. php:attr:: $type

   .. php:attr:: $name

   .. php:method:: GitObject::getName()

      :brief: Get the object's cached SHA-1 hash value.

      :returns: (string) $he hash value (binary sha1).

   .. php:method:: GitObject::getType()

      
      GIT::OBJ_BLOB.

      :brief: Get $he object's type.

      :returns: (integer) One of Git::OBJ_COMMIT, Git::OBJ_TREE or GET::OBJ_BLOB

   .. php:method:: GitObject::create()

   .. php:method:: GitObject::hash()

      current type with content $data.
      

      :brief: Internal function to calculate the hash value of a git object of the
      :param $data $string): The data to hash.

      :returns: (string) $he hash value (binary sha1).

   .. php:method:: GitObject::__construct()

      
      Never use this function except from a derived class. Use the
      constructor of a derived class, create() or Git::getObject() instead.

      :brief: Internal constructor for use from derived classes.

   .. php:method:: GitObject::unserialize()

      
      Note that the types of $this and the serialized object in $data have to
      match.

      it would be stored by git.

      :brief: Populate this object with values from its string representation.
      :param $data $string): The serialized representation of an object, as

   .. php:method:: GitObject::serialize()

      
      stored by git.

      :brief: Get the string representation of an object.

      :returns: The serialized representation of the object, as it would be

   .. php:method:: GitObject::rehash()

      
      You need to call this function after making changes to attributes in
      order to have getName() return the correct hash.

      :brief: Update $he SHA-1 name of an object.

   .. php:method:: GitObject::write()

      given at creation time.

      :brief: Write this object in its serialized form to the git repository

.. php:class:: GitCommit

   .. php:attr:: $tree

      string.

      :brief: (string) the tree referenced by this commit, as binary sha1

   .. php:attr:: $parents

      strings.

      :brief: (array if string) Parent commits of this commit, as binary sha1

   .. php:attr:: $author

      :brief: (GitCommitStamp) the author of this commit.

   .. php:attr:: $committer

      :brief: (GitCommitStamp) the committer of this commit.

   .. php:attr:: $summary

      :brief: (string) commit summary, i.e. the first line of the commit message.

   .. php:attr:: $detail

      :brief: (string) everything after the first line of the commit message.

   .. php:method:: GitCommit::__construct()

   .. php:method:: GitCommit::_unserialize()

   .. php:method:: GitCommit::_serialize()

   .. php:method:: GitCommit::getHistory()

      :brief: Get commit history in topological order.

      :returns: (array $f GitCommit)

   .. php:method:: GitCommit::getTree()

      :brief: Get the tree referenced by this commit.

      :returns: The GitTree referenced by this commit.

   .. php:method:: GitCommit::find()

      
      This is a convenience function calling GitTree::find() on the commit's
      tree.
      

      :copybrief: GitTree::find()

      :copydetails: GitTree::find()

   .. php:method:: GitCommit::treeDiff()

.. php:class:: GitCommitStamp

   .. php:attr:: $name

   .. php:attr:: $email

   .. php:attr:: $time

   .. php:attr:: $offset

   .. php:method:: GitCommitStamp::unserialize()

   .. php:method:: GitCommitStamp::serialize()

.. php:class:: GitTreeError

.. php:class:: GitTreeInvalidPathError

.. php:class:: GitTree

   .. php:attr:: $nodes

   .. php:method:: GitTree::__construct()

   .. php:method:: GitTree::_unserialize()

   .. php:method:: GitTree::nodecmp()

   .. php:method:: GitTree::_serialize()

   .. php:method:: GitTree::find()

      
      can happen if you are trying to treat a file like a directory (i.e.

      could be found.

      :brief: Find the tree or blob at a certain path.

      :throws: GitTreeInvalidPathError the path was found to be invalid. This

      :em: foo/bar $here @em foo is a file).
      :param $path $string): The path to look for, relative to this tree.

      :returns: The GitTree or GitBlob at the specified path, or NULL if none

   .. php:method:: GitTree::listRecursive()

      
      paths relative to the current tree, and the values are SHA-1 names of
      the corresponding blobs in binary representation.

      :brief: Recursively list the contents of a tree.

      :returns: (array mapping string to string) An array where the keys are

   .. php:method:: GitTree::updateNode()

      
      Missing directories in the path will be created automatically.

      cleared, i.e. the tree or blob shall be removed from this path.
      placed at the given path.

      created while updating the specified node. Those need to be written to
      the repository together with the modified tree.

      :brief: Updates a node in this tree.
      :param $path $string): Path to the node, relative to this tree.
      :param $mode $it: mode to set the node to. 0 if the node shall be
      :param $object $string): Binary SHA-1 hash of the object that shall be

      :returns: (array if GitObject) An array of GitObject's that were newly

   .. php:const:: GitTree:: TREEDIFF_A = 0x01;

   .. php:const:: GitTree:: TREEDIFF_B = 0x02;

   .. php:const:: GitTree:: TREEDIFF_REMOVED = self::TREEDIFF_A;

   .. php:const:: GitTree:: TREEDIFF_ADDED = self::TREEDIFF_B;

   .. php:const:: GitTree:: TREEDIFF_CHANGED = 0x03;

   .. php:method:: GitTree::treeDiff()

.. php:class:: GitBlob

   .. php:attr:: $data

      :brief: The data contained in this blob.

   .. php:method:: GitBlob::__construct()

   .. php:method:: GitBlob::_unserialize()

   .. php:method:: GitBlob::_serialize()

.. php:class:: Binary

   .. php:method:: Binary::uint16()

   .. php:method:: Binary::uint32()

   .. php:method:: Binary::nuint32()

   .. php:method:: Binary::fuint32()

   .. php:method:: Binary::nfuint32()

   .. php:method:: Binary::git_varint()