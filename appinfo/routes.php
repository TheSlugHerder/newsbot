return [
  'routes' => [
    ['name' => 'admin#saveSettings', 'url' => '/settings/save', 'verb' => 'POST'],
    ['name' => 'admin#postNow', 'url' => '/settings/post', 'verb' => 'POST'],
    ['name' => 'admin#getRooms', 'url' => '/settings/rooms', 'verb' => 'GET']
  ]
];