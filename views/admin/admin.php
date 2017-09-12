<?php $this->layout('layouts/default', ['title' => 'Redirects']); ?>

<div class="admin">
  <ul class="tabs" id="tabs">
    <li data-target="add" class="selected">Add a rule</li>
    <li data-target="test">Test a URL</li>
    <li data-target="logs">View Logs</li>
  </ul>

  <div class="tab-target target-selected" id="add">
    <div class="tab-content">
      <form action="/admin/add" method="post">
        <div class="redirect-form">
          <div class="form-element pattern">
            <label>Pattern</label>
            <input type="text" name="pattern" placeholder="https?://(www\.)?example\.com\/?(.*)">
          </div>
          <div class="form-element replacement">
            <label>Redirect</label>
            <input type="text" name="replacement" placeholder="http://www.test.com/$2">
          </div>
          <div class="submit">
            <button type="submit" class="button">Add Redirect</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="tab-target" id="test">
    <div class="tab-content">
      <div class="test-area" id="test-area">
        <div class="test-form" id="test-form">
          <div class="form-element test-url">
            <label>Test Url</label>
            <input type="text" placeholder="http://www.google.com" id="test-input">
          </div>
          <div class="form-element">
            <button class="button" id="test-button">Test</button>
          </div>
        </div>
        <div id="test-result"></div>
      </div>
    </div>
  </div>

  <div class="tab-target" id="logs">
    <div class="tab-content">
      <div class="test-form" id="test-form">
        <div class="form-element test-url">
          <label>Log Query</label>
          <?php $q = "SELECT COUNT(id) as `total`, `host` FROM logs WHERE DATE > '" . date('Y-m-d', strtotime('-1 month')) . "' GROUP BY `host` ORDER BY COUNT(id) DESC"; ?>
          <textarea type="text" placeholder="<?= $q ?>" id="query-field"><?= $q ?></textarea>
        </div>
        <div class="form-element">
          <button class="button" id="query-button">Check Logs</button>
        </div>
      </div>
      <div id="query-results">
      </div>
    </div>
  </div>
  
  <?php if ($redirects->count()) : ?>
  <ul class="redirects">
    <li class="redirect-header">
      <div class="redirect-handle"></div>
      <div class="redirect-pattern">Pattern</div>
      <div class="redirect-replacement">Replacement</div>
      <div class="redirect-redirects">Redirects</div>
      <div class="redirect-edit">Edit</div>
    </li>
    <?php foreach ($redirects as $redirect) : ?>
    <li class="redirect-item" data-id="<?= $redirect->id ?>">
      <div class="redirect-handle">&#9776;</div>
      <div class="redirect-pattern" &#9776; data-pattern="<?= $this->e($redirect->pattern)?>"><?= $this->e($redirect->pattern) ?></div>
      <div class="redirect-replacement" data-replacement="<?= $this->e($redirect->replacement)?>"><?= $this->e($redirect->replacement) ?></div>
      <div class="redirect-redirects"><?= $this->e($redirect->redirects) ?: 0 ?></div>
      <div class="redirect-edit">&#x270e;</div>
    </li>
    <?php endforeach; ?>
  </ul>
  <a href="#mode" class="mode-toggle" id="delete-toggle"><span class="delete">Remove a redirect</span></a>
  <?php else: ?>
  <dv class="state-message">No redirects have been added.</dv>
  <?php endif; ?>
</div>
