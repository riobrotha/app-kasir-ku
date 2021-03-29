<div class="header-area header-bottom">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-9  d-none d-lg-block">
                <div class="horizontal-menu">
                    <nav>
                        <ul id="nav_menu">

                            <li class="<?= $nav_title == "cashier" ? "active" : ""  ?>">
                                <a href="<?= base_url('cashier'); ?>"><i class="ti-layout-sidebar-left"></i>
                                    <span>Transaction</span></a>

                            </li>

                            <!-- <li class="mega-menu">
                                <a href="javascript:void(0)"><i class="ti-palette"></i><span>UI Features</span></a>
                                <ul class="submenu">
                                    <li><a href="accordion.html">Accordion</a></li>
                                    <li><a href="alert.html">Alert</a></li>
                                    <li><a href="badge.html">Badge</a></li>
                                    <li><a href="button.html">Button</a></li>
                                    <li><a href="button-group.html">Button Group</a></li>
                                    <li><a href="cards.html">Cards</a></li>
                                    <li><a href="dropdown.html">Dropdown</a></li>
                                    <li><a href="list-group.html">List Group</a></li>
                                    <li><a href="media-object.html">Media Object</a></li>
                                    <li><a href="modal.html">Modal</a></li>
                                    <li><a href="pagination.html">Pagination</a></li>
                                    <li><a href="popovers.html">Popover</a></li>
                                    <li><a href="progressbar.html">Progressbar</a></li>
                                    <li><a href="tab.html">Tab</a></li>
                                    <li><a href="typography.html">Typography</a></li>
                                    <li><a href="form.html">Form</a></li>
                                    <li><a href="grid.html">grid system</a></li>
                                </ul>
                            </li> -->


                            <li class="<?= $nav_title == "transaction_activity" ? "active" : "" ?>">
                                <a href="<?= base_url('activity'); ?>"><i class="ti-map-alt"></i> <span>Transaction Activity</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- nav and search button -->
            <div class="col-lg-3 clearfix">
                <div class="search-box">
                    <form action="#">
                        <input type="text" name="search" placeholder="Search..." required>
                        <i class="ti-search"></i>
                    </form>
                </div>
            </div>
            <!-- mobile_menu -->
            <div class="col-12 d-block d-lg-none">
                <div id="mobile_menu"></div>
            </div>
        </div>
    </div>
</div>