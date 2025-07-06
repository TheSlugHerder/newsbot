<?php
$config = \OC::$server->getConfig();
$feeds = $config->getAppValue('newsbot', 'feeds', '');
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
    <input type="text" name="feeds" value="<?= htmlspecialchars($feeds) ?>" />

    <label>Talk Room Tokens (comma-separated)</label>
    <input type="text" name="rooms" value="<?= htmlspecialchars($rooms) ?>" />

    <label>Bot Display Name</label>
    <input type="text" name="botname" value="<?= htmlspecialchars($botname) ?>" />

    <label>Post Interval (hours)</label>
    <select name="interval">
      <?php foreach ([1, 3, 6, 12] as $val): ?>
        <option value="<?= $val ?>" <?= $interval == $val ? 'selected' : '' ?>>
          Every <?= $val ?> hour<?= $val > 1 ? 's' : '' ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Category Filter (optional, comma-separated)</label>
    <input type="text" name="categories" value="<?= htmlspecialchars($categories) ?>" />

    <label>Use Markdown?</label>
    <input type="checkbox" name="markdown" value="yes" <?= $markdown === 'yes' ? 'checked' : '' ?> />

    <button type="submit">Save Settings</button>
    <button id="newsbot-postnow" type="button">Post News Now</button>
  </form>
</div>

<script>
document.getElementById('newsbot-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = new FormData(this);
  fetch(OC.generateUrl('/apps/newsbot/settings/save'), {
    method: 'POST',
    body: form
  }).then(() => alert('Saved!'));
});

document.getElementById('newsbot-postnow').addEventListener('click', function() {
  fetch(OC.generateUrl('/apps/newsbot/settings/post'), {
    method: 'POST'
  }).then(() => alert('News posted!'));
});
</script>
