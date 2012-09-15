<?php 
/**
 *                        MM.
 *                        MNNNN.
 *                        MM...NMN.
 *                        MN......ONN
 *                        MN.......ZN
 *                        MN.......ZN
 *                        MNZ......ZN         MMMMMMM  MMMMMMM  MMMMMMM  MMMMM
 *                      ,ND."MND...ON         MM   MM  MM   MM  MM  MMM  MM   MM
 *                   ,NNN..NN. "MNDON         MM   MM  MM   MM  MM       MM   MM
 *                ,NNN.......DNN. "ON         MMMMMMM  MMMMMMM  MMMMMMM  MM   MM
 *              NNM..............NNM                   MM
 *          ,N N. "NN7........ONN" ,N                  MM                MM
 *       ,NNNN NNNN."ONN...ZNN" ,NMON                  MM                MM
 *    ,NNI..ON NO...NN. "NN" ,NN...MN                                    MM
 *  NN......ON NZ......NN INN......MN         MMMMMMM  MMMMMMM  MMMMMMM  MMMMMMM
 *  N.......ON NZ.......N $N.......MN         MM   MM  MM   MM  MM   MM  MM   MM
 *  N.......ZN N$.......N $N.......MN         MM   MM  MM   MM  MM   MM  MM   MM
 *  N.......MN NN.......N $N.......MN         MMMMMMM  MMMMMMM  MMMMMMM  MMMMMMM
 *  N....NN$"    "NN....N $N....NN",ON.            MM
 *  N.NNN"          "N..N $N..N" ,MN...MN.         MM
 *  NI"                "N $N" ,NM........"MM.      MM
 *                          MM...............MM
 *                           "MMM.........MNM"
 *                              "MMN...NMM"
 *                                 "NMM"                                           
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @see /qoob/core/open_qoob.php
 */
//_________________________________________________________________________________
//                                                         framework initialization
// --- version
define("QOOB_VERSION", "1.0");

//---lazyness
define("SLASH",       DIRECTORY_SEPARATOR);

// --- server
define("QOOB_ROOT",		dirname(__FILE__));
define("APP_PATH",		QOOB_ROOT.SLASH."app");
define("QOOB_PATH",		QOOB_ROOT.SLASH."qoob");

// --- default controller + action
define("DEFAULT_CONTROLLER", "pages");
define("DEFAULT_ACTION", "index");

date_default_timezone_set("America/New_York");
//_________________________________________________________________________________
//                                                              framework execution
require_once QOOB_PATH.SLASH."core".SLASH."open_qoob.php";
new open_qoob();

?>