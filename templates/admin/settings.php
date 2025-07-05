// HTML/PHP settings UI included in original canvas above
// templates/admin/settings.php
script('newsbot', 'settings');
style('newsbot', 'admin');
$config = \OC::$server->getConfig();
$feeds = $config->getAppValue('newsbot', 'feeds', 'https://www.aljazeera.com/xml/rss/all.xml,https://feeds.reuters.com/reuters/topNews');
$rooms = $config->getAppValue('newsbot', 'rooms', '');
$botname = $config->getAppValue('newsbot', 'botname', 'NewsBot');
$interval = $config->getAppValue('newsbot', 'interval', '3');
$categories = $config->getAppValue('newsbot', 'categories', '');
$markdown = $config->getAppValue('newsbot', 'markdown', 'no');

?>
<div id="newsbot-settings">
  <h2>NewsBot Settings</h2>
  <form id="newsbot-form">
    <label>RSS Feed URLs (comma-separated)</label>
    <input type="text" name="feeds" value="<?= $feeds ?>" />
    <label>Talk Room Tokens (comma-separated)</label>
    <input type="text" name="rooms" value="<?= $rooms ?>" />
    <label>Bot Display Name</label>
    <input type="text" name="botname" value="<?= $botname ?>" />
    <label>Post Interval (hours)</label>
    <select name="interval">
      <option value="1" <?= $interval=='1'?'selected':'' ?>>Every hour</option>
      <option value="3" <?= $interval=='3'?'selected':'' ?>>Every 3 hours</option>
      <option value="6" <?= $interval=='6'?'selected':'' ?>>Every 6 hours</option>
      <option value="12" <?= $interval=='12'?'selected':'' ?>>Every 12 hours</option>
    </select>
    <label>Category Filter (comma-separated)</label>
    <input type="text" name="categories" value="<?= $categories ?>" />
    <label>Markdown Preview?</label>
    <input type="checkbox" name="markdown" value="yes" <?= $markdown=='yes'?'checked':'' ?> />
    <button type="submit">Save Settings</button>
    <button id="newsbot-postnow" type="button">Post News Now</button>
  </form>
</div>
<script>
document.getElementById('newsbot-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = new FormData(this);
  fetch(OC.generateUrl('/apps/newsbot/settings/save'), {
    method: 'POST', body: data
  }).then(() => alert('Saved.'));
});
document.getElementById('newsbot-postnow').addEventListener('click', function() {
  fetch(OC.generateUrl('/apps/newsbot/settings/post'), { method: 'POST' })
    .then(() => alert('News posted!'));
});
</script>
