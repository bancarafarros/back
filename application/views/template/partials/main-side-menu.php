<nav id="left-sidebar-nav" class="sidebar-nav">
    <ul id="main-menu" class="metismenu animation-li-delay">
        <li class="<?php echo active_page('dashboard', 'active') ?>"><a href="<?php echo site_url('dashboard') ?>"><i class="fas fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li class="header">Apps</li>
        <?php if (in_array('masjid.access', $userMenus)) : ?>
            <li class="<?php echo active_page('masjid', 'active') ?>">
                <a href="<?php echo site_url('masjid/index') ?>"><i class="fas fa-mosque"></i> <span>Masjid</span></a>
            </li>
        <?php endif; ?>
        <?php if (in_array('pengurus.access', $userMenus)) : ?>
            <li class="<?php echo active_page('pengurus', 'active') ?>">
                <a href="<?php echo site_url('pengurus/index') ?>"><i class="fa-solid fa-user"></i> <span>Pengurus</span></a>
            </li>
        <?php endif; ?>
        <?php if (in_array('jamaah.access', $userMenus)) : ?>
            <li class="<?php echo active_page('jamaah', 'active') ?>">
                <a href="<?php echo site_url('jamaah/index') ?>"><i class="fa-solid fa-users"></i> <span>Jamaah</span></a>
            </li>
        <?php endif; ?>
        <?php if (in_array('faq.access', $userMenus)) : ?>
            <li class="<?php echo active_page('faq', 'active') ?>">
                <a href="<?php echo site_url('faq/index') ?>"><i class="fa-solid fa-question-circle"></i> <span>FAQ</span></a>
            </li>
        <?php endif; ?>
        <?php if (in_array("sistem.access", $userMenus)) : ?>
            <li class="<?= active_page('sistem', 'active') ?>">
                <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                    <span class="icon-holder">
                        <i class="fas fa-cogs"></i>
                    </span>
                    <span class="title">Setting</span>
                    <span class="arrow">
                        <i class="arrow-icon"></i>
                    </span>
                </a>
                <ul class="collapse">
                    <?php if (in_array('usergroup.access', $userMenus)) : ?>
                        <li class="<?php echo active_page('usergroup', 'active') ?>">
                            <a href="<?php echo site_url('setting/groups') ?>"><span>User Groups</span></a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('modul.access', $userMenus)) : ?>
                        <li class="<?php echo active_page('modul', 'active') ?>">
                            <a href="<?php echo site_url('setting/modules') ?>"> <span>Modul Sistem</span></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
</nav>