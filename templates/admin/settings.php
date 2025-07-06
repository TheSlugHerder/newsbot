<?PHP
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
    <select name="rooms[]" id="talk-rooms-select" multiple>
        <!-- Populated via JS -->
    </select>
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
document.addEventListener('DOMContentLoaded', () => {
  fetch(OC.generateUrl('/apps/newsbot/settings/rooms'))
    .then(r => r.json())
    .then(data => {
      const select = document.getElementById('talk-rooms-select');
      if (!data.ocs || !data.ocs.data) return;

      const selectedRooms = "<?= $rooms ?>".split(',');

      data.ocs.data.forEach(room => {
        const opt = document.createElement('option');
        opt.value = room.token;
        opt.textContent = `${room.displayName} (${room.token})`;
        if (selectedRooms.includes(room.token)) {
          opt.selected = true;
        }
        select.appendChild(opt);
      });
    });
});
document.getElementById('newsbot-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = new FormData(this);
  const selectedRooms = Array.from(document.getElementById('talk-rooms-select').selectedOptions)
                             .map(opt => opt.value)
                             .join(',');
  form.set('rooms', selectedRooms);
  fetch(OC.generateUrl('/apps/newsbot/settings/save'), {
    method: 'POST', body: form
  }).then(() => alert('Saved.'));
});
document.getElementById('newsbot-postnow').addEventListener('click', function() {
  fetch(OC.generateUrl('/apps/newsbot/settings/post'), { method: 'POST' })
    .then(() => alert('News posted!'));
});
</script>
