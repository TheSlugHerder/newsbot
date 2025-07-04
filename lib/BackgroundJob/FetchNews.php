namespace OCA\NewsBot\BackgroundJob;

use OCP\BackgroundJob\TimedJob;
use OCA\NewsBot\Service\NewsService;
use OC;

class FetchNews extends TimedJob {
  protected function run($argument) {
    $svc = OC::$server->query(NewsService::class);
    $svc->postNews();
  }
}
