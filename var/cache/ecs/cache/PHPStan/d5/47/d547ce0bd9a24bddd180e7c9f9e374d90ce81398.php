<?php declare(strict_types = 1);

// odsl-/var/www/app/
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v1',
   'data' => 
  array (
    '/var/www/app/Router.php' => 
    array (
      0 => 'c29eaceba866669e6783b2ee9ac41c7f2fea54b6',
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
      0 => '9cc030e419a8acdb7f9218f0a87813240281cde4',
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
      0 => '9e11b0b7f0fa1fbf0cddae896727700912459385',
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
      0 => '8e7fe252e5b9ee677e66f74d01f8b756424f7212',
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
      0 => '24c4fcd8bb4776ff1fb4be612146c84fa2fd6c55',
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
      0 => '04e2e947f1201394f77b324ccf67f7707d509f4f',
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
      0 => '5881ec0109fde6468b35bb914d6a8933677fed52',
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
      0 => 'c1e9274c5ab43d2376790f287ceae6c95a7f2da7',
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
      0 => '0ebd0435ffce2af06b07056f0d5abc83c7880271',
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
      0 => '3b4bf9ab4d7438493076470efdb14eb82175aec0',
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
      0 => 'a43e306c9b8fd8b82847514d69659cf50ffc8518',
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
      0 => '4048163528e993fed38bdc6057027a92cf7d26d2',
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
      0 => 'ef4c903d76e3ab5bfd2564ba9e9b4394376a9193',
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
      0 => '3835d1a5ee799be1ec7785e7846ffccc893e66b6',
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
      0 => '68475ede130e9dc4a1025a07479fb38163ae5352',
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
      0 => 'ad4e193a390384cdd5bdfe33f486e9a2b19e0887',
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
      0 => '96db2ed46399930f0d149d6baaeb4561446e4ec3',
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
      0 => 'aff16c4bdfe654e2101a570e252ce205e6de36b4',
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
      0 => 'e465a4f7b7d1fd80511e8df63625c0fa4c48532f',
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
      0 => '8f124208383c383c2c1f0e42e1a878d49cb5de4f',
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
      0 => '54d8df39e13186b9b266ef7b41baaa521067a198',
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
      0 => '7c8e3e3f0c78f071e8ccff6c8372d10422520135',
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
      0 => '87fcccf1202286546f13614e80d5f3b14ff0f6ab',
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