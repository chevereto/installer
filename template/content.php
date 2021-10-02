<?php if ($pageId == 'error') { ?>
  <div id="screen-error" class="screen screen--error">
    <div class="flex-box error-box">
      <div>
        <h1>Aw, Snap!</h1>
        <p>Your web server lacks some requirements that must be fixed to install <?php echo APPLICATION_FULL_NAME; ?>.</p>
        <p>Please check:</p>
        <ul>
          <?php
            foreach ($requirementsCheck->errors as $v) {
                ?>
            <li><?php echo $v; ?></li>
          <?php
            } ?>
        </ul>
        <p>If you already fixed your web server then make sure to restart it to apply changes. If the problem persists, contact your server administrator.</p>
        <p class="error-box-code">Server <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
      </div>
    </div>
  </div>
<?php } else { ?>
  <div id="screen-welcome" class="screen screen--show animate animate--slow">
    <div class="header flex-item"><a href="<?php echo VENDOR['url']; ?>" target="_blank"><?php echo $svgLogo; ?></a></div>
    <div class="flex-box flex-item">
      <div>
        <h1><?php echo APPLICATION_FULL_NAME; ?> Installer</h1>
        <p>This tool will guide you through the process of installing Chevereto. To proceed, check the information below.</p>
        <ul>
          <li>Server path <code><?php echo $runtime->absPath; ?></code></li>
          <li>Website url <code><?php echo $runtime->rootUrl; ?></code></li>
        </ul>
        <p>Confirm that the above details match to where you want to install Chevereto and that there's no other software installed.</p>
        <?php
          if (preg_match('/nginx/i', $runtime->serverSoftware)) { ?>
          <p class="highlight">Take note on the <a href="<?php echo $runtime->rootUrl . $runtime->installerFilename . '?getNginxRules'; ?>" target="_blank">nginx server rules</a> that should be already applied to your <code>nginx.conf</code> server block. If those aren't provided this installer will fail to complete the process.</p>
        <?php } ?>
        <div>
          <button class="action radius" data-action="show" data-arg="license">Continue</button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-license" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Enter license key</h1>
        <p>A license key is required to install <?php echo APPLICATION_FULL_NAME; ?>.</p>
        <p class="p alert"></p>
        <div class="p input-label">
          <label for="installKey">License key</label>
          <input class="radius width-100p" type="password" name="installKey" id="installKey" placeholder="Paste your V<?php echo APPLICATION['version']; ?> license key here" autofill="off" autocomplete="off">
          <div><small>You can find the license key at your <a href="https://chevereto.com/panel/license" target="_blank">client panel</a>.</small></div>
        </div>
        <div>
          <button class="action radius" data-action="setLicense" data-arg="installKey">Enter license key</button>
          <a class="button radius" href="https://chevereto.com/pricing" target="_blank">Purchase</a>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-upgrade" class="screen animate animate--slow">
    <div class="header flex-item"><?php echo $svgLogo; ?></div>
    <div class="flex-box col-width">
      <div>
        <h1>Upgrade</h1>
        <p>A license key is required to upgrade to <?php echo APPLICATION_FULL_NAME; ?>.</p>
        <p>The system database schema will change, and the system files will get replaced. Don't forget to backup.</p>
        <p>Your system settings, previous uploads, and all user-generated content will remain there.</p>
        <p class="p alert"></p>
        <div class="p input-label">
          <label for="upgradeKey">License key</label>
          <input class="radius width-100p" type="password" name="upgradeKey" id="upgradeKey" placeholder="Paste your V<?php echo APPLICATION['version']; ?> license key here">
          <div><small>You can find the license key at your <a href="https://chevereto.com/panel/license" target="_blank">client panel</a>.</small></div>
        </div>
        <div>
          <button class="action radius" data-action="setUpgrade" data-arg="upgradeKey">Enter license key</button>
          <a class="button radius" href="https://chevereto.com/pricing" target="_blank">Purchase</a>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-db" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Database</h1>
        <p><?php echo APPLICATION_FULL_NAME; ?> requires a MySQL 8 (5.7 min), MariaDB 10 database.</p>
        <?php if(isDatabaseEnvProvided()) { ?>
        <p class="highlight"><b>Note:</b> ✨ Database values are being automatically provided using environment variables.</p>
        <?php } ?>
        <?php
            function echoDatabaseEnv(string $env, string $default): void {
                echo 'placeholder="' . $default . '" ';
                $getEnv = getenv($env);
                if($getEnv !== false) {
                    echo 'value="' . getenv($env) .'" readonly';
                }
            }
        ?>
        <form method="post" name="database" data-trigger="setDb" autocomplete="off">
          <p class="p alert"></p>
          <div class="p input-label">
            <label for="dbHost">Host</label>
            <input class="radius width-100p" type="text" name="dbHost" id="dbHost"
            <?php
                echoDatabaseEnv('CHEVERETO_DB_HOST', 'Hostname or IP');
            ?>
            required>
          </div>
          <div class="p input-label">
            <label for="dbPort">Port</label>
            <input class="radius width-100p" type="number" name="dbPort" id="dbPort"
            <?php
                echoDatabaseEnv('CHEVERETO_DB_PORT', '3306');
            ?>
            required>
          </div>
          <div class="p input-label">
            <label for="dbName">Name</label>
            <input class="radius width-100p" type="text" name="dbName" id="dbName"
            <?php
                echoDatabaseEnv('CHEVERETO_DB_NAME', 'Database name');
            ?>
            required>
          </div>
          <div class="p input-label">
            <label for="dbUser">User</label>
            <input class="radius width-100p" type="text" name="dbUser" id="dbUser"
            <?php
                echoDatabaseEnv('CHEVERETO_DB_USER', 'Database username');
            ?>
            required>
            <div><small>The database user must have ALL PRIVILEGES on the target database.</small></div>
          </div>
          <div class="p input-label">
            <label for="dbUserPassword">User password</label>
            <input class="radius width-100p" type="password" name="dbUserPassword" id="dbUserPassword"
            <?php
                echoDatabaseEnv('CHEVERETO_DB_PASS', 'Database username password');
            ?>
            >
          </div>
          <div>
            <button class="action radius">
            <?php 
                echo isDatabaseEnvProvided() ? 'Check' : 'Set';
            ?> Database
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="screen-ready" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Ready to install</h1>
        <p>The installer is ready to download and install the latest <?php echo APPLICATION_FULL_NAME; ?> release in <code><?php echo $runtime->absPath; ?></code></p>
        <p class="highlight">By installing is understood that you accept the <a href="https://chevereto.com/license" target="_blank">Chevereto EULA</a>.</p>
        <div>
          <button class="action radius" data-action="install">Install <?php echo APPLICATION_FULL_NAME; ?></button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-ready-upgrade" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Ready to upgrade</h1>
        <p>The installer is ready to download and upgrade to the latest Chevereto release in <code><?php echo $runtime->absPath; ?></code></p>
        <p class="highlight">By upgrading is understood that you accept the <a href="https://chevereto.com/license" target="_blank">Chevereto EULA</a>.</p>
        <div>
          <button class="action radius" data-action="upgrade">Upgrade to <?php echo APPLICATION_FULL_NAME; ?></button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-installing" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Installing</h1>
        <p>The software is being installed. Don't close this window until the process gets completed.</p>
        <p class="p alert"></p>
        <div class="log log--install p"></div>
      </div>
    </div>
  </div>

  <div id="screen-upgrading" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Upgrading</h1>
        <p>The software is being upgraded. Don't close this window until the process gets completed.</p>
        <p class="p alert"></p>
        <div class="log log--upgrade p"></div>
      </div>
    </div>
  </div>

  <div id="screen-complete" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Installation completed</h1>
        <p>You can now create your admin account and configure your website to fit your needs.</p>
        <p class="alert">Double-check if the installer file was removed from <code><?php echo INSTALLER_FILEPATH; ?></code></p>
        <p>Take note on the installation details below.</p>
        <div class="install-details p highlight font-size-80p"></div>
        <p>💖 Hope you enjoy using Chevereto.</p>
        <div>
          <a class="button action radius" href="<?php echo $runtime->rootUrl; ?>" target="_blank">Open website</a>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-complete-upgrade" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Upgrade prepared</h1>
        <p>The system files have been upgraded. You can now install the upgrade which will perform the database changes needed and complete the process.</p>
        <p class="alert">Double-check if the installer file was removed from <code><?php echo INSTALLER_FILEPATH; ?></code></p>
        <div>
          <a class="button action radius" href="<?php echo $runtime->rootUrl; ?>install">Install upgrade</a>
        </div>
      </div>
    </div>
  </div>

<?php } ?>