<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo base_url('/'); ?>" class="brand-link">
      <img src="<?php echo base_url('public/themes/icon'); ?>/user.jpg" alt="iSales" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">iSales Admin</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo base_url('public/themes/icon'); ?>/user.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo session()->get('nama') == NULL ? "Administrator" : session()->get('nama');?></a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo base_url('/admin'); ?>" class="nav-link <?= session()->getFlashdata('active') == "admin" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <?php if(session()->get('role') == '1') { ?>
                <!-- ADMIN -->
                <li class="nav-item">
                    <a href="<?php echo base_url('admin/payment_method'); ?>" class="nav-link <?= session()->getFlashdata('active') == "payment_method" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>Payment Method</p>
                    </a>
                </li>

                <li class="nav-item has-treeview <?= session()->getFlashdata('mainactive') == "campaign" ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            Campaign
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/campaign/upload'); ?>" class="nav-link <?= session()->getFlashdata('active') == "upload" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-upload"></i>
                                <p>Upload Campaign</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/campaign/share'); ?>" class="nav-link <?php echo session()->getFlashdata('active') == "share" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-share"></i>
                                <p>Share Campaign</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/recording'); ?>" class="nav-link <?= session()->getFlashdata('active') == "recording" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-file-audio"></i>
                        <p>Recording</p>
                    </a>
                </li>

                 <li class="nav-item">
                    <a href="<?php echo base_url('admin/user'); ?>" class="nav-link <?= session()->getFlashdata('active') == "user" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Users</p>
                    </a>
                </li>

                <li class="nav-item has-treeview <?= session()->getFlashdata('mainactive') == "campaign" ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-gift"></i>
                        <p>
                            Data Produk
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/produk'); ?>" class="nav-link <?= session()->getFlashdata('active') == "upload" ? "active" : "" ?>">
                                <i class="nav-icon  fas fa-users"></i>
                                <p>Life Protection 20 </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/produk/index'); ?>" class="nav-link <?php echo session()->getFlashdata('active') == "share" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-ambulance"></i>
                                <p>Perlindungan Kecelakaan</p>
                            </a>
                        </li>
                    </ul>
                </li>
              
                <li class="nav-item has-treeview <?= session()->getFlashdata('mainactive') == "campaign" ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>
                            Data Premi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/premi/'); ?>" class="nav-link <?= session()->getFlashdata('active') == "upload" ? "active" : "" ?>">
                                <i class="nav-icon fas fas fa-users"></i>
                                <p>Life Protection 20</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/premi/index'); ?>" class="nav-link <?php echo session()->getFlashdata('active') == "share" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-ambulance"></i>
                                <p>Perlindungan Kecelakaan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/performance'); ?>" class="nav-link <?= session()->getFlashdata('active') == "performance" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-download"></i>
                        <p>Performance</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/helper'); ?>" class="nav-link <?= session()->getFlashdata('active') == "helper" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Script Helper</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/leadergroup'); ?>" class="nav-link <?= session()->getFlashdata('active') == "leadergroup" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Leader Group</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/spaj'); ?>" class="nav-link <?= session()->getFlashdata('active') == "spaj" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>SPAJ</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/virtual_account'); ?>" class="nav-link <?= session()->getFlashdata('active') == "virtual_account" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Virtual Account</p>
                    </a>
                </li>

                <?php } else { ?>
                <!-- USER -->
                 <li class="nav-item">
                    <a href="<?php echo base_url('admin/user'); ?>" class="nav-link <?= session()->getFlashdata('active') == "user" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Users</p>
                    </a>
                </li>

                <li class="nav-item has-treeview <?= session()->getFlashdata('mainactive') == "campaign" ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-gift"></i>
                        <p>
                            Produk
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <li class="nav-item has-treeview <?= session()->getFlashdata('mainactive') == "campaign" ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-gift"></i>
                        <p>
                            Data Produk
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/produk'); ?>" class="nav-link <?= session()->getFlashdata('active') == "upload" ? "active" : "" ?>">
                                <i class="nav-icon  fas fa-users"></i>
                                <p>Life Protection 20 </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/produk/index'); ?>" class="nav-link <?php echo session()->getFlashdata('active') == "share" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-ambulance"></i>
                                <p>Perlindungan Kecelakaan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?= session()->getFlashdata('mainactive') == "campaign" ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>
                            Data Premi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/premi/'); ?>" class="nav-link <?= session()->getFlashdata('active') == "upload" ? "active" : "" ?>">
                                <i class="nav-icon fas fas fa-users"></i>
                                <p>Life Protection 20</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/premi/index'); ?>" class="nav-link <?php echo session()->getFlashdata('active') == "share" ? "active" : "" ?>">
                                <i class="nav-icon fas fa-ambulance"></i>
                                <p>Perlindungan Kecelakaan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/performance'); ?>" class="nav-link <?= session()->getFlashdata('active') == "performance" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-download"></i>
                        <p>Performance</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/helper'); ?>" class="nav-link <?= session()->getFlashdata('active') == "helper" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Script Helper</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/leadergroup'); ?>" class="nav-link <?= session()->getFlashdata('active') == "leadergroup" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Leader Group</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/spaj'); ?>" class="nav-link <?= session()->getFlashdata('active') == "spaj" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>SPAJ</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('admin/virtual_account'); ?>" class="nav-link <?= session()->getFlashdata('active') == "virtual_account" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Virtual Account</p>
                    </a>
                </li>

                   <li class="nav-item">
                    <a href="<?php echo base_url('admin/recording'); ?>" class="nav-link <?= session()->getFlashdata('active') == "recording" ? "active" : "" ?>">
                        <i class="nav-icon fas fa-file-audio"></i>
                        <p>Recording</p>
                    </a>
                </li>

                <?php }?>
                <li class="nav-header">ACCOUNT</li>
                <li class="nav-item">
                    <a href="<?php echo base_url('admin/logout'); ?>" class="nav-link logout">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text">Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>