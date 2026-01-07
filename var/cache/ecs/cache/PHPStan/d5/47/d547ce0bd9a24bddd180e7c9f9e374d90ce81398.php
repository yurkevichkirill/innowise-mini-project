<?php declare(strict_types = 1);

// odsl-/var/www/app/
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v1',
   'data' => 
  array (
    '/var/www/app/Router.php' => 
    array (
      0 => '6f2f527d3048c200c6427720a7c0e43bf0fbe330',
      1 => 
      array (
        0 => 'app\\router',
      ),
      2 => 
      array (
        0 => 'app\\__construct',
        1 => 'app\\initializecontrollers',
        2 => 'app\\registerfromcontroller',
        3 => 'app\\getcontrollerfiles',
        4 => 'app\\controllerfiletoclass',
        5 => 'app\\register',
        6 => 'app\\handler',
        7 => 'app\\createdynamicdata',
        8 => 'app\\normalizepath',
        9 => 'app\\callhandler',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/App.php' => 
    array (
      0 => '09c28b9d3474cbcd6312fe7496179bae4258c9ab',
      1 => 
      array (
        0 => 'app\\app',
      ),
      2 => 
      array (
        0 => 'app\\__construct',
        1 => 'app\\run',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Response.php' => 
    array (
      0 => '116221595e2cdf59448b497580b370a8f8f36559',
      1 => 
      array (
        0 => 'app\\response',
      ),
      2 => 
      array (
        0 => 'app\\__construct',
        1 => 'app\\getprotocolversion',
        2 => 'app\\withprotocolversion',
        3 => 'app\\getheaders',
        4 => 'app\\hasheader',
        5 => 'app\\getheader',
        6 => 'app\\getheaderline',
        7 => 'app\\withheader',
        8 => 'app\\withaddedheader',
        9 => 'app\\withoutheader',
        10 => 'app\\getbody',
        11 => 'app\\withbody',
        12 => 'app\\createstream',
        13 => 'app\\getstatuscode',
        14 => 'app\\withstatus',
        15 => 'app\\getreasonphrase',
        16 => 'app\\normalizeheaders',
        17 => 'app\\normalizeheadervalue',
        18 => 'app\\getdefaultreason',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Logger.php' => 
    array (
      0 => 'd7d394418da0e183e32e0555db925800329257a6',
      1 => 
      array (
        0 => 'app\\logger',
      ),
      2 => 
      array (
        0 => 'app\\__construct',
        1 => 'app\\emergency',
        2 => 'app\\alert',
        3 => 'app\\critical',
        4 => 'app\\error',
        5 => 'app\\warning',
        6 => 'app\\notice',
        7 => 'app\\info',
        8 => 'app\\debug',
        9 => 'app\\log',
        10 => 'app\\interpolate',
        11 => 'app\\islevelenabled',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Models/UserDTO.php' => 
    array (
      0 => 'd257a823db9bf139ddf1c241f0ab99811d606ea2',
      1 => 
      array (
        0 => 'app\\models\\userdto',
      ),
      2 => 
      array (
        0 => 'app\\models\\__construct',
        1 => 'app\\models\\getid',
        2 => 'app\\models\\getname',
        3 => 'app\\models\\getage',
        4 => 'app\\models\\getmoney',
        5 => 'app\\models\\ishasvisa',
        6 => 'app\\models\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Container.php' => 
    array (
      0 => '1875e170ed6cc4c294b67829c43a32a743837923',
      1 => 
      array (
        0 => 'app\\container',
      ),
      2 => 
      array (
        0 => 'app\\__construct',
        1 => 'app\\has',
        2 => 'app\\get',
        3 => 'app\\prepareobject',
        4 => 'app\\singleton',
        5 => 'app\\bind',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Services/UserService.php' => 
    array (
      0 => 'dc9103c38245c141219cda94f20d98cc87dc26ef',
      1 => 
      array (
        0 => 'app\\services\\userservice',
      ),
      2 => 
      array (
        0 => 'app\\services\\__construct',
        1 => 'app\\services\\create',
        2 => 'app\\services\\update',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Services/UserServiceInterface.php' => 
    array (
      0 => '6219df37d47c5dcc4a4a96fc343a323354ad7b99',
      1 => 
      array (
        0 => 'app\\services\\userserviceinterface',
      ),
      2 => 
      array (
        0 => 'app\\services\\create',
        1 => 'app\\services\\update',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Services/UserRepositoryInterface.php' => 
    array (
      0 => '65f1902b33a1093fe27d12807e90307bb1875a47',
      1 => 
      array (
        0 => 'app\\services\\userrepositoryinterface',
      ),
      2 => 
      array (
        0 => 'app\\services\\getall',
        1 => 'app\\services\\get',
        2 => 'app\\services\\delete',
        3 => 'app\\services\\save',
        4 => 'app\\services\\existuser',
        5 => 'app\\services\\getlastid',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Services/UserRepository.php' => 
    array (
      0 => 'f456d0b0b91274f338ed104cfe97cb9ef4a580f5',
      1 => 
      array (
        0 => 'app\\services\\userrepository',
      ),
      2 => 
      array (
        0 => 'app\\services\\__construct',
        1 => 'app\\services\\getall',
        2 => 'app\\services\\get',
        3 => 'app\\services\\save',
        4 => 'app\\services\\delete',
        5 => 'app\\services\\existuser',
        6 => 'app\\services\\getlastid',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Services/HttpTransformInterface.php' => 
    array (
      0 => 'fc6b4f8d5faad9c335387a98e2c41ca6e3e5163f',
      1 => 
      array (
        0 => 'app\\services\\httptransforminterface',
      ),
      2 => 
      array (
        0 => 'app\\services\\alltojson',
        1 => 'app\\services\\onetojson',
        2 => 'app\\services\\errortojson',
        3 => 'app\\services\\getlastsegment',
        4 => 'app\\services\\getargs',
        5 => 'app\\services\\alltohtml',
        6 => 'app\\services\\onetohtml',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Services/ConnectionServiceInterface.php' => 
    array (
      0 => '226f2e479db04611e065e3bf016ecb73120266db',
      1 => 
      array (
        0 => 'app\\services\\connectionserviceinterface',
      ),
      2 => 
      array (
        0 => 'app\\services\\getconnection',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Services/HttpTransform.php' => 
    array (
      0 => '102a3f437bbd317269b59331378301c831a675fb',
      1 => 
      array (
        0 => 'app\\services\\httptransform',
      ),
      2 => 
      array (
        0 => 'app\\services\\alltojson',
        1 => 'app\\services\\onetojson',
        2 => 'app\\services\\errortojson',
        3 => 'app\\services\\getlastsegment',
        4 => 'app\\services\\getargs',
        5 => 'app\\services\\alltohtml',
        6 => 'app\\services\\onetohtml',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Request.php' => 
    array (
      0 => 'ae8115c634fe4b8120813ccb74ac257a0ef46d12',
      1 => 
      array (
        0 => 'app\\request',
      ),
      2 => 
      array (
        0 => 'app\\__construct',
        1 => 'app\\getprotocolversion',
        2 => 'app\\withprotocolversion',
        3 => 'app\\getheaders',
        4 => 'app\\hasheader',
        5 => 'app\\getheader',
        6 => 'app\\getheaderline',
        7 => 'app\\withheader',
        8 => 'app\\normalizeheaders',
        9 => 'app\\normalizeheadervalue',
        10 => 'app\\withaddedheader',
        11 => 'app\\withoutheader',
        12 => 'app\\getbody',
        13 => 'app\\withbody',
        14 => 'app\\createstream',
        15 => 'app\\getrequesttarget',
        16 => 'app\\withrequesttarget',
        17 => 'app\\getmethod',
        18 => 'app\\withmethod',
        19 => 'app\\geturi',
        20 => 'app\\withuri',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/DB.php' => 
    array (
      0 => '696a3ad13ecee1577d96e63a7e50e210c0df5dfc',
      1 => 
      array (
        0 => 'app\\db',
      ),
      2 => 
      array (
        0 => 'app\\__construct',
        1 => 'app\\getconnection',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Exceptions/UserNotFoundException.php' => 
    array (
      0 => '5a4478c6ade098393777934eacf8e81abd3a0eae',
      1 => 
      array (
        0 => 'app\\exceptions\\usernotfoundexception',
      ),
      2 => 
      array (
        0 => 'app\\exceptions\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Controllers/WebController.php' => 
    array (
      0 => 'be8068275ccb7d6fec3188bfe236648dec9a323e',
      1 => 
      array (
        0 => 'app\\controllers\\webcontroller',
      ),
      2 => 
      array (
        0 => 'app\\controllers\\__construct',
        1 => 'app\\controllers\\index',
        2 => 'app\\controllers\\showallusers',
        3 => 'app\\controllers\\showuser',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Controllers/APIController.php' => 
    array (
      0 => 'f637f9b658830bd4539fb9d356c618fe836635e6',
      1 => 
      array (
        0 => 'app\\controllers\\apicontroller',
      ),
      2 => 
      array (
        0 => 'app\\controllers\\__construct',
        1 => 'app\\controllers\\showall',
        2 => 'app\\controllers\\show',
        3 => 'app\\controllers\\store',
        4 => 'app\\controllers\\update',
        5 => 'app\\controllers\\remove',
        6 => 'app\\controllers\\notfound',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Attributes/Get.php' => 
    array (
      0 => '64ba253e1dc81cc05fa3a0c98bc0cf9068b62b86',
      1 => 
      array (
        0 => 'app\\attributes\\get',
      ),
      2 => 
      array (
        0 => 'app\\attributes\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Attributes/Post.php' => 
    array (
      0 => '63dda884af535e0824a476db2459d3bfa4f07d15',
      1 => 
      array (
        0 => 'app\\attributes\\post',
      ),
      2 => 
      array (
        0 => 'app\\attributes\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Attributes/Delete.php' => 
    array (
      0 => '8b5bc70b7b3e6de0e3ba0ef1ca7e438c5617aa4d',
      1 => 
      array (
        0 => 'app\\attributes\\delete',
      ),
      2 => 
      array (
        0 => 'app\\attributes\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Attributes/Patch.php' => 
    array (
      0 => '2f09b168819800fc2802355493b3510912b94192',
      1 => 
      array (
        0 => 'app\\attributes\\patch',
      ),
      2 => 
      array (
        0 => 'app\\attributes\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Attributes/Route.php' => 
    array (
      0 => '8b6d0674d34df558e0c6b51a33e6f283886bd7ee',
      1 => 
      array (
        0 => 'app\\attributes\\route',
      ),
      2 => 
      array (
        0 => 'app\\attributes\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Attributes/FromEnv.php' => 
    array (
      0 => '8f8975283dc74448a96db5949db4f5a9783c4049',
      1 => 
      array (
        0 => 'app\\attributes\\fromenv',
      ),
      2 => 
      array (
        0 => 'app\\attributes\\__construct',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Exceptions/ContainerException.php' => 
    array (
      0 => 'ae8cd6a2de46fcb3616ff4c71d820103ba12fcaa',
      1 => 
      array (
        0 => 'app\\exceptions\\containerexception',
      ),
      2 => 
      array (
        0 => 'app\\exceptions\\__construct',
      ),
      3 => 
      array (
      ),
    ),
  ),
));