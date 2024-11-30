
 <ul class="app-nav">
  <div class="dropdown">
    <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications">
      <i class="fa fa-bell-o fa-lg"></i></a>
      <div class="app-notification dropdown-menu dropdown-menu-right">
        <li class="app-notification__title">No tienes Notificaciones (0).</li>
      </div>
    </div>
    <!-- User Menu-->
    <div class="dropdown">
      <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">
        <em class="fa fa-user fa-lg"></em></a>
        <div class="app-notification dropdown-menu dropdown-menu-right">

          <?php  if ($_SESSION['role_id'] !=1) { ?>

          <div class="app-notification__content">
            <div><li class="app-notification__item" onclick="cargar_contenido('Contenido_principal','../view/prolife/view_change_password.php')"  style="cursor: pointer;">
              <span class="app-notification__icon">
                <span class="fa-stack fa-lg">
                  <i class="fa fa-key"></i>
                </span>
              </span>
              <div>
                <p class="app-notification__message">Settings</p>
                <p class="app-notification__meta">cambiar contraseña</p>
              </div>
            </li>
          </div>
        </div>
        <div class="app-notification__content">
          <div><li class="app-notification__item" onclick="cargar_contenido('Contenido_principal','../view/prolife/view_show_profile.php')"  style="cursor: pointer;">
            <span class="app-notification__icon">
              <span class="fa-stack fa-lg">
                <i class="fa fa-user fa-lg "></i>
              </span>
            </span>
            <div>
              <p class="app-notification__message">Perfil</p>
              <p class="app-notification__meta">perfil individual</p>
            </div>
          </li>
        </div>
      </div>
      <?php } ?>
      <div class="app-notification__content">
        <div><li class="app-notification__item" onclick="distroysession()" style="cursor: pointer;">
          <span class="app-notification__icon">
            <span class="fa-stack fa-lg">
              <i class="fa fa-power-off"></i>
            </span>
          </span>
          <div>
            <p class="app-notification__message">salir</p>
            <p class="app-notification__meta">cerrar session</p>
          </div>
        </li>
      </div>
    </div>
  </div>
</div>
</ul>



