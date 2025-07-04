namespace OCA\NewsBot\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\IConfig;
use OCA\NewsBot\Service\NewsService;

class AdminController extends Controller {
  private $config;
  private $service;

  public function __construct($AppName, IRequest $request, IConfig $config, NewsService $service) {
    parent::__construct($AppName, $request);
    $this->config = $config;
    $this->service = $service;
  }

  public function saveSettings(): JSONResponse {
    $this->config->setAppValue('newsbot', 'feeds', $this->request->getParam('feeds'));
    $this->config->setAppValue('newsbot', 'rooms', $this->request->getParam('rooms'));
    $this->config->setAppValue('newsbot', 'botname', $this->request->getParam('botname'));
    $this->config->setAppValue('newsbot', 'interval', $this->request->getParam('interval'));
    $this->config->setAppValue('newsbot', 'categories', $this->request->getParam('categories'));
    $this->config->setAppValue('newsbot', 'markdown', $this->request->getParam('markdown', 'no'));
    return new JSONResponse(['status' => 'success']);
  }

  public function postNow(): JSONResponse {
    $this->service->postNews();
    return new JSONResponse(['status' => 'posted']);
  }
}
