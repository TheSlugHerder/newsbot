namespace OCA\NewsBot\Service;

use OCP\IConfig;

class NewsService {
  private $config;
  public function __construct(IConfig $config) {
    $this->config = $config;
  }

  public function postNews() {
    $feeds = explode(',', $this->config->getAppValue('newsbot', 'feeds', ''));
    $rooms = explode(',', $this->config->getAppValue('newsbot', 'rooms', ''));
    $markdown = $this->config->getAppValue('newsbot', 'markdown', 'no') === 'yes';

    foreach ($feeds as $feedUrl) {
      $feed = simplexml_load_file(trim($feedUrl));
      $items = array_slice($feed->channel->item, 0, 3);
      $message = "📰 News Update:\n";
      foreach ($items as $item) {
        $title = (string)$item->title;
        $link = (string)$item->link;
        $message .= ($markdown ? "- [{$title}]({$link})\n" : "- {$title}: {$link}\n");
      }

      foreach ($rooms as $room) {
        $this->postToTalk(trim($room), $message);
      }
    }
  }

  private function postToTalk(string $room, string $message) {
    $botuser = 'newsbot';
    $apppass = 'changeme';
    $url = "https://your-nextcloud-domain/ocs/v2.php/apps/spreed/api/v1/chat/$room";
    $headers = [
      'OCS-APIRequest: true',
      'Content-Type: application/x-www-form-urlencoded'
    ];
    $post = http_build_query(['message' => $message]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, "$botuser:$apppass");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_exec($ch);
    curl_close($ch);
  }
}
