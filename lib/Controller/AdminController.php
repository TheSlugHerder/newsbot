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

  public function getRooms(): JSONResponse {
    $botuser = 'newsbot';
    $apppass = 'changeme'; // Consider securing this via config or secret store
    $url = 'https://your-nextcloud-domain/ocs/v2.php/apps/spreed/api/v1/room';
    $headers = ['OCS-APIRequest: true'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, "$botuser:$apppass");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$resp || $code !== 200) {
        return new JSONResponse(['error' => 'Failed to fetch rooms'], 500);
    }

    return new JSONResponse(json_decode($resp, true));
  }
}
