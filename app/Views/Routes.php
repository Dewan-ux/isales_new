<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('landingpage/(:alpha)', 'LandingPageController::index/$1', ['namespace' => 'App\Controllers\LandingPage']);
$routes->get('landingpage/(:alpha)/create', 'LandingPageController::create/$1', ['namespace' => 'App\Controllers\LandingPage']);

// $routes->add('landingpage/submit', 'LandingPageController::create', ['namespace' => 'App\Controllers\LandingPage']);
$routes->group('landingpage', function($routes){
	$routes->add('submit', 'VisitorController::create', ['namespace' => 'App\Controllers\LandingPage']);
	$routes->add('(:alpha)/add', 'LandingPageController::add/$1', ['namespace' => 'App\Controllers\LandingPage']);
	$routes->add('(:alpha)/success', 'LandingPageController::success', ['namespace' => 'App\Controllers\LandingPage']);
	$routes->add('create/(:page)', 'LandingPageController::create', ['namespace' => 'App\Controllers\LandingPage']);
	// $routes->add('belanjaonline(:page)', 'LandingPageController::belanja/', ['namespace' => 'App\Controllers\LandingPage']);
	$routes->add('foto', 'VisitorController::foto', ['namespace' => 'App\Controllers\LandingPage']);
	$routes->group('cms', function($routes)
	{
		$routes->add('create', 'VisitorController::createCms', ['namespace' => 'App\Controllers\LandingPage']);
		$routes->add('/', 'VisitorController::listCmsLandingPage', ['namespace' => 'App\Controllers\LandingPage']);
		$routes->add('update', 'VisitorController::updateCmsLandingPage', ['namespace' => 'App\Controllers\LandingPage']);
		$routes->add('delete', 'VisitorController::deleteCmsLandingPage', ['namespace' => 'App\Controllers\LandingPage']);
	});

});
$routes->group('pekerjaan', function($routes)
{
	$routes->add('list', 'PekerjaanController::pekerjaanList', ['namespace' => 'App\Controllers\Pekerjaan']);

});

$routes->group('auth', function($routes)
{
	$routes->add('master/create', 'AuthController::mastercreate', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('list', 'AuthController::listAuth', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('user/id', 'AuthController::user', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('profile', 'AuthController::profile', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('group', 'AuthController::listAuthGroup', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('group/update', 'AuthController::updateAuthGroup', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('update', 'AuthController::updateAuth', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('delete', 'AuthController::deleteAuth', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('changepassword', 'AuthController::changePassword', ['namespace' => 'App\Controllers\Auth']);

	$routes->add('create', 'AuthController::create', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('demo', 'AuthController::demo', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('forcelogout', 'AuthController::force_logout', ['namespace' => 'App\Controllers\Auth']);

	$routes->add('login', 'AuthController::doLogin', ['namespace' => 'App\Controllers\Auth']);
	$routes->add('logout', 'AuthController::doLogout', ['namespace' => 'App\Controllers\Auth']);
});

//Gw yang tambahin nih wil routenya API
$routes->group('payment', function($routes)
{
	$routes->add('master/create', 'PaymentController::mastercreate', ['namespace' => 'App\Controllers\Payment']);
	$routes->add('list', 'PaymentController::listPayment', ['namespace' => 'App\Controllers\Payment']);
	$routes->add('id', 'PaymentController::payment', ['namespace' => 'App\Controllers\Payment']);
	$routes->add('update', 'PaymentController::updatePayment', ['namespace' => 'App\Controllers\Payment']);
	$routes->add('delete', 'PaymentController::deletePayment', ['namespace' => 'App\Controllers\Payment']);
	$routes->add('create', 'PaymentController::create', ['namespace' => 'App\Controllers\Payment']);
});

$routes->group('produk', function($routes)
{
	$routes->add('master/create', 'ProdukController::mastercreate', ['namespace' => 'App\Controllers\Produk']);
	$routes->add('list', 'ProdukController::listProduk', ['namespace' => 'App\Controllers\Produk']);
	$routes->add('id', 'ProdukController::produk', ['namespace' => 'App\Controllers\Produk']);
	$routes->add('update', 'ProdukController::updateProduk', ['namespace' => 'App\Controllers\Produk']);
	$routes->add('delete', 'ProdukController::deleteProduk', ['namespace' => 'App\Controllers\Produk']);
	$routes->add('create', 'ProdukController::create', ['namespace' => 'App\Controllers\Produk']);

});

$routes->group('premi', function($routes)
{
	$routes->add('master/create', 'PremiController::mastercreate', ['namespace' => 'App\Controllers\Premi']);
	$routes->add('list', 'PremiController::listPremi', ['namespace' => 'App\Controllers\Premi']);
	$routes->add('id', 'PremiController::premi', ['namespace' => 'App\Controllers\Premi']);
	$routes->add('update', 'PremiController::updatePremi', ['namespace' => 'App\Controllers\Premi']);
	$routes->add('delete', 'PremiController::deletePremi', ['namespace' => 'App\Controllers\Premi']);
	$routes->add('create', 'PremiController::create', ['namespace' => 'App\Controllers\Premi']);

});

$routes->group('sales', function($routes)
{
	$routes->add('master/create', 'SalesController::mastercreate', ['namespace' => 'App\Controllers\Sales']);
	$routes->add('list', 'SalesController::listSales', ['namespace' => 'App\Controllers\Sales']);
	$routes->add('id', 'SalesController::sales', ['namespace' => 'App\Controllers\Sales']);
	$routes->add('update', 'SalesController::updateSales', ['namespace' => 'App\Controllers\Sales']);
	$routes->add('delete', 'SalesController::deleteSales', ['namespace' => 'App\Controllers\Sales']);
	$routes->add('create', 'SalesController::create', ['namespace' => 'App\Controllers\Sales']);
});

$routes->group('berita', function($routes)
{
	$routes->add('master/create', 'BeritaController::mastercreate', ['namespace' => 'App\Controllers\Berita']);
	$routes->add('list', 'BeritaController::listBerita', ['namespace' => 'App\Controllers\Berita']);
	$routes->add('id', 'BeritaController::berita', ['namespace' => 'App\Controllers\Berita']);
	$routes->add('update', 'BeritaController::updateBerita', ['namespace' => 'App\Controllers\Berita']);
	$routes->add('delete', 'BeritaController::deleteBerita', ['namespace' => 'App\Controllers\Berita']);
	$routes->add('create', 'BeritaController::create', ['namespace' => 'App\Controllers\Berita']);
});

$routes->group('virtual_account', function($routes)
{
	$routes->add('master/create', 'VirtualAccountController::mastercreate', ['namespace' => 'App\Controllers\VirtualAccount']);
	$routes->add('/', 'VirtualAccountController::listVa', ['namespace' => 'App\Controllers\VirtualAccount']);
	$routes->add('uploadva', 'VirtualAccountController::uploadVa', ['namespace' => 'App\Controllers\VirtualAccount']);
});

$routes->group('tags', function($routes)
{
	$routes->add('master/create', 'TagsController::mastercreate', ['namespace' => 'App\Controllers\Tags']);
	$routes->add('list', 'TagsController::listTags', ['namespace' => 'App\Controllers\Tags']);
	$routes->add('id', 'TagsController::tags', ['namespace' => 'App\Controllers\Tags']);
	$routes->add('update', 'TagsController::updateTags', ['namespace' => 'App\Controllers\Tags']);
	$routes->add('delete', 'TagsController::deleteTags', ['namespace' => 'App\Controllers\Tags']);
	$routes->add('create', 'TagsController::create', ['namespace' => 'App\Controllers\Tags']);
});

$routes->group('admin', function($routes)
{
	$routes->add('/', 'AdminController::index', ['namespace' => 'App\Controllers\Admin']);
	$routes->add('login', 'AdminController::login', ['namespace' => 'App\Controllers\Admin']);
	$routes->add('doLogin', 'AdminAuthController::login', ['namespace' => 'App\Controllers\Admin']);
	$routes->add('logout', 'AdminAuthController::logout', ['namespace' => 'App\Controllers\Admin']);
	$routes->add('dashboard', 'AdminDashboardController::listDashboard', ['namespace' => 'App\Controllers\Admin']);
	$routes->add('listcampaign', 'AdminDashboardController::listCampaignVisitors', ['namespace' => 'App\Controllers\Admin']);
	$routes->add('visitors', 'AdminController::visitors', ['namespace' => 'App\Controllers\Admin']);

	$routes->group('user', function($routes){
		$routes->add('/', 'UserController::index', ['namespace' => 'App\Controllers\User']);
		$routes->add('create', 'UserController::create', ['namespace' => 'App\Controllers\User']);
		$routes->add('add', 'UserController::add', ['namespace' => 'App\Controllers\User']);
		$routes->add('edit/(:num)', 'UserController::edit/$1', ['namespace' => 'App\Controllers\User']);
		$routes->add('update', 'UserController::update', ['namespace' => 'App\Controllers\User']);
		$routes->add('forcelogout', 'UserController::forceLogout', ['namespace' => 'App\Controllers\User']);
		$routes->add('delete/(:num)', 'UserController::delete/$1', ['namespace' => 'App\Controllers\User']);

	});

	$routes->group('produk', function($routes){
		$routes->add('/', 'ProductController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('create', 'ProductController::create', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('add', 'ProductController::add', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('edit/(:num)', 'ProductController::edit/$1', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('update', 'ProductController::update', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('delete/(:num)', 'ProductController::delete/$1', ['namespace' => 'App\Controllers\Admin']);

	});

	$routes->group('leadergroup', function($routes){
		$routes->add('/', 'LeaderGroupController::index', ['namespace' => 'App\Controllers\LeaderGroup']);
		$routes->add('tsr', 'LeaderGroupController::getTsr', ['namespace' => 'App\Controllers\LeaderGroup']);
		$routes->add('update', 'LeaderGroupController::update', ['namespace' => 'App\Controllers\LeaderGroup']);
		$routes->add('leaderid', 'LeaderGroupController::getLeaderId', ['namespace' => 'App\Controllers\LeaderGroup']);
	});

	$routes->group('produk', function($routes){
		$routes->add('/', 'ProductController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('create', 'ProductController::create', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('add', 'ProductController::add', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('edit/(:num)', 'ProductController::edit/$1', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('update', 'ProductController::update', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('delete/(:num)', 'ProductController::delete/$1', ['namespace' => 'App\Controllers\Admin']);
	});
	$routes->group('payment_method', function($routes){
		$routes->add('/', 'AdminPaymentController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('create', 'AdminPaymentController::create', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('add', 'AdminPaymentController::add', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('edit/(:num)', 'AdminPaymentController::edit/$1', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('update', 'AdminPaymentController::update', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('delete/(:num)', 'AdminPaymentController::delete/$1', ['namespace' => 'App\Controllers\Admin']);
	});
	$routes->group('helper', function($routes){
		$routes->add('/', 'AdminSalesController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('create', 'AdminSalesController::create', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('add', 'AdminSalesController::add', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('preview/(:num)', 'AdminSalesController::preview/$1', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('delete/(:num)', 'AdminSalesController::delete/$1', ['namespace' => 'App\Controllers\Admin']);
	});
	$routes->group('performance', function($routes){
		$routes->add('/', 'AdminPerformanceController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('export/dpr', 'ReportingController::getExportDpr', ['namespace' => 'App\Controllers\Reporting']);
		$routes->add('export/apr', 'ReportingController::getExportApr', ['namespace' => 'App\Controllers\Reporting']);
	});
	$routes->group('premi', function($routes){
		$routes->add('/', 'AdminPremiController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('create', 'AdminPremiController::create', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('add', 'AdminPremiController::add', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('edit/(:num)', 'AdminPremiController::edit/$1', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('update', 'AdminPremiController::update', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('delete/(:num)', 'AdminPremiController::delete/$1', ['namespace' => 'App\Controllers\Admin']);
	});
	$routes->group('berita', function($routes){
		$routes->add('/', 'AdminBeritaController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('create', 'AdminBeritaController::create', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('add', 'AdminBeritaController::add', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('edit/(:num)', 'AdminBeritaController::edit/$1', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('update', 'AdminBeritaController::update', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('delete/(:num)', 'AdminBeritaController::delete/$1', ['namespace' => 'App\Controllers\Admin']);
	});
	$routes->group('tags', function($routes){
		$routes->add('/', 'AdminTagsController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('create', 'AdminTagsController::create', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('add', 'AdminTagsController::add', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('edit/(:num)', 'AdminTagsController::edit/$1', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('update', 'AdminTagsController::update', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('delete/(:num)', 'AdminTagsController::delete/$1', ['namespace' => 'App\Controllers\Admin']);
	});
	$routes->group('cms', function($routes){
		$routes->add('/', 'AdminLandingPageController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('create', 'AdminLandingPageController::create', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('add', 'AdminLandingPageController::add', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('edit/(:num)', 'AdminLandingPageController::edit/$1', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('update', 'AdminLandingPageController::update', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('delete/(:num)', 'AdminLandingPageController::delete/$1', ['namespace' => 'App\Controllers\Admin']);
	});

	$routes->group('virtual_account', function($routes){
		$routes->add('/', 'AdminVaController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('upload', 'AdminVaController::uploadVa', ['namespace' => 'App\Controllers\Admin']);
	});

	$routes->group('spaj', function($routes){
		$routes->add('/', 'AdminSpajController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('get', 'AdminSpajController::getData', ['namespace' => 'App\Controllers\Admin']);
	});
	
	$routes->group('campaign', function($routes){
		$routes->add('upload', 'AdminCampaignController::index', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('uploading', 'AdminCampaignController::uploadCampaign', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('share', 'AdminCampaignController::shareCampaign', ['namespace' => 'App\Controllers\Admin']);
		$routes->add('sharing', 'AdminCampaignController::doShareCampaign', ['namespace' => 'App\Controllers\Admin']);
	});
});

$routes->group('performance', function($routes)
{
	$routes->add('/', 'PerformanceController::dashboardList', ['namespace' => 'App\Controllers\Performance']);
	$routes->add('export/dpr', 'PerformanceController::getExportDpr', ['namespace' => 'App\Controllers\Performance']);
	$routes->add('export/apr', 'PerformanceController::getExportApr', ['namespace' => 'App\Controllers\Performance']);
	$routes->add('report', 'PerformanceController::reporting', ['namespace' => 'App\Controllers\Performance']);
	$routes->add('report/url', 'PerformanceController::reportingUrl', ['namespace' => 'App\Controllers\Performance']);
});

$routes->group('report', function($routes)
{
	$routes->add('export/reporting', 'ReportingController::exportReporting', ['namespace' => 'App\Controllers\Reporting']);
	$routes->add('export/dpr', 'ReportingController::exportDpr', ['namespace' => 'App\Controllers\Reporting']);
	$routes->add('export/apr', 'ReportingController::exportApr', ['namespace' => 'App\Controllers\Reporting']);
});

$routes->group('leader', function($routes)
{
	$routes->group('leads', function($routes)
	{
		$routes->add('list', 'LeaderController::updateOrder', ['namespace' => 'App\Controllers\Leader']);
		$routes->add('recording/list', 'RecordingController::recordingByExtension', ['namespace' => 'App\Controllers\Recording']);
		// $routes->add('tsr', 'QaController::listTsr', ['namespace' => 'App\Controllers\Qa']);
		// $routes->add('id', 'LeaderController::detailOrder', ['namespace' => 'App\Controllers\Leader']);
		$routes->add('tsr/id', 'LeaderController::listTsr', ['namespace' => 'App\Controllers\Leader']);
	});



	$routes->add('dashboard', 'LeaderController::dashboardList', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('dashboard/export/dpr', 'LeaderController::exportDpr', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('dashboard/export/apr', 'LeaderController::exportApr', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('resetshare', 'LeaderController::reset', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('performance', 'LeaderController::performance', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('performance/tsr/id', 'LeaderController::performanceById', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('shareleads', 'LeaderController::shareLeads', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('do/share', 'LeaderController::doShare', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('tsr/log', 'LeaderController::tsrLog', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('tsr/log/id', 'LeaderController::tsrLogID', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('call/interfrensi/start', 'LeaderController::startInterfrensi', ['namespace' => 'App\Controllers\Leader']);
	$routes->add('call/interfrensi/end', 'LeaderController::endInterfrensi', ['namespace' => 'App\Controllers\Leader']);
});

$routes->group('tsr', function($routes)
{
	$routes->group('order', function($routes)
	{
		$routes->add('create', 'TelesalesController::createOrder', ['namespace' => 'App\Controllers\Telesales']);
		$routes->add('update', 'TelesalesController::updateOrder', ['namespace' => 'App\Controllers\Telesales']);
		$routes->add('/', 'TelesalesController::listOrder', ['namespace' => 'App\Controllers\Telesales']);
		$routes->add('id', 'TelesalesController::detailOrder', ['namespace' => 'App\Controllers\Telesales']);
		$routes->add('listproduct', 'TelesalesController::productList', ['namespace' => 'App\Controllers\Telesales']);
		$routes->add('premibyproduk', 'TelesalesController::premiByProduk', ['namespace' => 'App\Controllers\Telesales']);
		$routes->add('listpertanyaankesehatan', 'TelesalesController::pertanyaanList', ['namespace' => 'App\Controllers\Telesales']);
	});
	$routes->add('scriptsales', 'TelesalesController::scriptsales', ['namespace' => 'App\Controllers\Telesales']);
	$routes->add('performance', 'TelesalesController::performance', ['namespace' => 'App\Controllers\Telesales']);
	$routes->add('leads', 'TelesalesController::leads', ['namespace' => 'App\Controllers\Telesales']);
	$routes->add('lead/id', 'TelesalesController::leadById', ['namespace' => 'App\Controllers\Telesales']);
	$routes->add('call/start', 'TelesalesController::startCall', ['namespace' => 'App\Controllers\Telesales']);
	$routes->add('call/end', 'TelesalesController::endCall', ['namespace' => 'App\Controllers\Telesales']);
	$routes->add('call/req/interfrensi', 'TelesalesController::reqInterfrensi', ['namespace' => 'App\Controllers\Telesales']);
});


$routes->group('qa', function($routes)
{
	$routes->group('order', function($routes)
	{
		$routes->add('tsr/id', 'QaController::listOrder', ['namespace' => 'App\Controllers\Qa']);
		$routes->add('recording/list', 'RecordingController::recordingByExtension', ['namespace' => 'App\Controllers\Recording']);
		$routes->add('id', 'QaController::detailOrder', ['namespace' => 'App\Controllers\Qa']);
		$routes->add('tsr', 'QaController::listTsr', ['namespace' => 'App\Controllers\Qa']);
		$routes->add('spaj', 'QaController::listSpaj', ['namespace' => 'App\Controllers\Qa']);
		$routes->add('checked', 'QaController::checkOrder', ['namespace' => 'App\Controllers\Qa']);
	});
});

$routes->group('services', function($routes)
{
	$routes->add('resetshare', 'ServicesController::reset', ['namespace' => 'App\Controllers\Services']);
	$routes->add('get/recording', 'ServicesController::getRecording', ['namespace' => 'App\Controllers\Services']);
});

$routes->group('news', function($routes)
{
	$routes->add('tags', 'NewsController::listTags', ['namespace' => 'App\Controllers\News']);
	$routes->add('latestlimit', 'NewsController::latestBeritaLimit', ['namespace' => 'App\Controllers\News']);
	$routes->add('id', 'NewsController::pages', ['namespace' => 'App\Controllers\News']);
	$routes->add('category/id', 'NewsController::beritaByCategory', ['namespace' => 'App\Controllers\News']);
	$routes->add('checkip', 'NewsController::checkExistIp', ['namespace' => 'App\Controllers\News']);
});

$routes->group('region', function($routes)
{
	$routes->add('province', 'RegionController::provinces', ['namespace' => 'App\Controllers\Region']);
	$routes->add('city', 'RegionController::cities', ['namespace' => 'App\Controllers\Region']);
	$routes->add('postal_code', 'RegionController::postal_code', ['namespace' => 'App\Controllers\Region']);
});

$routes->group('campaign', function($routes)
{
	$routes->add('/', 'CampaignController::logUpload', ['namespace' => 'App\Controllers\Campaign']);
	$routes->add('upload', 'CampaignController::updloadCampaign', ['namespace' => 'App\Controllers\Campaign']);
	$routes->add('log_share', 'CampaignController::logShare', ['namespace' => 'App\Controllers\Campaign']);
	$routes->add('share_campaign', 'CampaignController::shareCampaign', ['namespace' => 'App\Controllers\Campaign']);
});
/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
