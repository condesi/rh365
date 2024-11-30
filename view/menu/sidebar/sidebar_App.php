<?php
if (!isset($_SESSION))
  session_start();
 require '../controller/user/ControllerSessionActive.php';
?>

  <div class="app-sidebar__user">
    <img class="app-sidebar__user-avatar" src="<?= isset($_SESSION['photo']) ? substr($_SESSION['photo'], 3) : ''; ?>" alt="User Image">
        <div>
          <p class="app-sidebar__user-name"><?= $_SESSION['username']; ?></p>
          
        </div>
      </div>

 <ul class="app-menu">
     <li style="text-align: center;">
          <input class="app-search__input" id="input_search" type="text" placeholder="Search" onkeyup="SearchVlueSidebar(this)">
         
        </li>

          <?php  if (isAccess(1)) { ?>
        <li>
          <a class="app-menu__item active" onclick="cargar_contenido('Contenido_principal','../view/dashboard/view_dashboard.php');toggleActiveClass(this)">
            <i class="app-menu__icon fa fa-dashboard"></i>
            <span class="app-menu__label">Dashboard</span>
             <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
        </li>
          <?php } ?>

          <?php  if (isAccess(2)) { ?>
        <li>
          <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/user/view_list_user.php');toggleActiveClass(this)">
            <i class="app-menu__icon fa fa fa-user"></i>
            <span class="app-menu__label">Usuarios</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
        </li>
          <?php } ?>

        <?php  if (isAccess(3)) { ?>
          
          <li>
          <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/role/view_list_roles.php');toggleActiveClass(this)">
            <i class="app-menu__icon fa fa-unlock"></i>
            <span class="app-menu__label">Roles</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          </li>

            <?php } ?>

          <?php  if (isAccess(4)) { ?>
         <li>
          <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/personal/view_listar_personal.php');toggleActiveClass(this)">
            <i class="app-menu__icon fa fa-users"></i>
            <span class="app-menu__label">Personal</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
        </li>
          <?php } ?>

  <?php  if (isAccess(17)) { ?>
           <li class="treeview ">
          <a class="app-menu__item"  onclick="toggleActiveClass(this)" data-toggle="treeview">
            <i class="app-menu__icon fa fa-map-marker"></i>
            <span class="app-menu__label">Puntos control</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" onclick="cargar_contenido('Contenido_principal','../view/google/view_checkpoint.php')"><i class="icon fa fa-circle-o"></i>Puestos</a>
            </li>
            <li><a class="treeview-item"  onclick="cargar_contenido('Contenido_principal','../view/google/view_management_checkpoint.php')"><i class="icon fa fa-circle-o"></i>Gest. puestos</a>
            </li>

          </ul>
        </li>
        <?php } ?>

       <?php  if (isAccess(18)) { ?>
        <li>
          <a class="app-menu__item" onclick="cargar_contenido('Contenido_principal','../view/google/view_list_trakers.php') ;toggleActiveClass(this)" >
            <i class="app-menu__icon fa fa-sign-out"></i>
            <span class="app-menu__label">Trakers</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
        </li>
       <?php } ?>

        <?php  if (isAccess(5)) { ?>
         <li>
          <a class="app-menu__item" onclick="cargar_contenido('Contenido_principal','../view/jornadas/view_listar_inicio.php') ;toggleActiveClass(this)" >
            <i class="app-menu__icon fa fa-briefcase"></i>
            <span class="app-menu__label">Jornadas</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
        </li>
          <?php } ?>

        <?php  if (isAccess(6)) { ?>
         <li class="treeview">
          <a class="app-menu__item" onclick="toggleActiveClass(this)" data-toggle="treeview">
            <i class="app-menu__icon fa fa-money"></i>
            <span class="app-menu__label">Pagos</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
              <li><a class="treeview-item"  onclick="cargar_contenido('Contenido_principal','../view/pagos/view_listar_pagosjornadas.php')"><i class="icon fa fa-circle-o"></i>Pagos Jornadas</a>
            </li>
            <li><a class="treeview-item"  onclick="cargar_contenido('Contenido_principal','../view/pagos/view_listar_pagoshextra.php')"><i class="icon fa fa-circle-o"></i>Pagos Extras</a>
            </li>
           
           

          </ul>
        </li>
          <?php } ?>

        <?php  if (isAccess(7)) { ?>
         <li>
          <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/adelantos/view_listar_personas.php') ;toggleActiveClass(this)">
            <i class="app-menu__icon fa fa-handshake-o"></i>
            <span class="app-menu__label">Adelantos</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
        </li>
          <?php } ?>

        <?php  if (isAccess(8)) { ?>
         <li>
          <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/extras/view_lista_hextras.php') ;toggleActiveClass(this)">
            <i class="app-menu__icon fa fa-clock-o"></i>
            <span class="app-menu__label">Extras</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
        </li>
          <?php } ?>
        <?php  if (isAccess(9)) { ?>
          <li>
          <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/vacaciones/view_lista_personas.php');toggleActiveClass(this)">
            <i class="app-menu__icon fa fa-plane"></i>
            <span class="app-menu__label">Vacaciones</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
        </li>
          <?php } ?>
        <?php  if (isAccess(10)) { ?>
         <li>
          <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/asistencia/view_listar_persona.php');toggleActiveClass(this)">
            <i class="app-menu__icon fa fa-check-square-o"></i>
            <span class="app-menu__label">Asistencia</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
        </li>
          <?php } ?>

        <?php  if (isAccess(11)) { ?>
        <li class="treeview">
          <a class="app-menu__item"  onclick="toggleActiveClass(this)" data-toggle="treeview">
            <i class="app-menu__icon fa fa-file-pdf-o"></i>
            <span class="app-menu__label">Reportes</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" onclick="cargar_contenido('Contenido_principal','../view/reporte/view_report_pagos.php')"><i class="icon fa fa-circle-o"></i>Rept. Pagos Jornadas</a>
            </li>
            <li><a class="treeview-item"  onclick="cargar_contenido('Contenido_principal','../view/reporte/view_report_extra.php')"><i class="icon fa fa-circle-o"></i>Rept. Pagos H. Extras</a>
            </li>
            <li><a class="treeview-item" onclick="cargar_contenido('Contenido_principal','../view/reporte/view_report_asistencia.php')"><i class="icon fa fa-circle-o"></i>Asistencia</a>
            </li>
            <li><a class="treeview-item" onclick="cargar_contenido('Contenido_principal','../view/reporte/view_report_adelantos.php')"><i class="icon fa fa-circle-o"></i>Adelantos</a>
            </li>
            <li><a class="treeview-item" onclick="cargar_contenido('Contenido_principal','../view/reporte/view_report_vacaciones.php')"><i class="icon fa fa-circle-o"></i>Vacaciones</a>
            </li>
            <li><a class="treeview-item" onclick="cargar_contenido('Contenido_principal','../view/reporte/view_report_permission.php')"><i class="icon fa fa-circle-o"></i>Permisos</a>
            
            </li>
          </ul>
        </li>
      <?php } ?>

     
    <?php  if (isAccess(12)) { ?>

      <li>
        <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/shifts/view_list_shifts.php','shifts');toggleActiveClass(this)">
          <i class="app-menu__icon fa fa-calendar-check-o"></i>
          <span class="app-menu__label">Turnos</span>
          <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
      </li>
    <?php } ?>
    <?php  if (isAccess(13)) { ?>
      <li>
        <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/entry/view_entry.php','entry');toggleActiveClass(this)">
          <i class="app-menu__icon fa fa-grav"></i>
          <span class="app-menu__label">Panel</span>
          <i class="treeview-indicator fa fa-angle-right"></i>
        </a>

      </li>
    <?php } ?>
    <?php  if (isAccess(14)) { ?>
      <li>
        <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/panel/view_list_panel.php','entry');toggleActiveClass(this)">
          <i class="app-menu__icon fa fa-address-card-o"></i>
          <span class="app-menu__label">Resumen G.</span>
          <i class="treeview-indicator fa fa-angle-right"></i>
        </a>

      </li>
    <?php } ?>
     <?php  if (isAccess(15)) { ?>
        
      <li>
        <a class="app-menu__item " onclick="cargar_contenido('Contenido_principal','../view/company/view_show_config.php','company');toggleActiveClass(this)">
          <i class="app-menu__icon fa fa-university"></i>
          <span class="app-menu__label">Empresa</span>
          <i class="treeview-indicator fa fa-angle-right"></i>
        </a>

      </li>
    <?php } ?>
 <?php  if (isAccess(16)) { ?>
      
        <li class="treeview ">
          <a class="app-menu__item"  onclick="toggleActiveClass(this)" data-toggle="treeview">
            <i class="app-menu__icon fa fa-child"></i>
            <span class="app-menu__label">Permisos</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" onclick="cargar_contenido('Contenido_principal','../view/permission/view_category.php')"><i class="icon fa fa-circle-o"></i>Categoria</a>
            </li>
            <li><a class="treeview-item"  onclick="cargar_contenido('Contenido_principal','../view/permission/view_permission.php')"><i class="icon fa fa-circle-o"></i>Gest. Permisos</a>
            </li>

          </ul>
        </li>
        <?php } ?>
      
  </ul>


   