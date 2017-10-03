<?php if(!empty($this->session->userdata['id'])&& $this->session->userdata['type']=='admin'){?>
                        <li class="sidenav-heading">Menu</li>
                        <li class="sidenav-item has-subnav">
                            <a href="#" aria-haspopup="true">
                                <span class="sidenav-icon icon icon-lock"></span>
                                <span class="sidenav-label">Admin Nav</span>
                            </a>
                            <ul class="sidenav-subnav collapse">
                                <li class="sidenav-subheading">Admin Menu</li>
                                <li><a href="<?php echo base_url().'admin/add_menu'?>" >Add Menu</a></li>
                                <li><a href="<?php echo base_url().'admin/manage_admin_menu'?>" >Manage</a></li>
                            </ul>
                        </li>
                        <li class="sidenav-item has-subnav">
                            <a href="#" aria-haspopup="true">
                                <span class="sidenav-icon icon icon-lock"></span>
                                <span class="sidenav-label">User Nav</span>
                            </a>
                            <ul class="sidenav-subnav collapse">
                                <li class="sidenav-subheading">User Navigation</li>
                                <li><a href="<?php echo base_url().'admin/add_team_menu'?>" >Add Menu</a></li>
                                <li><a href="<?php echo base_url().'admin/manage_team_menu'?>" >Manage</a></li>
                            </ul>
                        </li>
                        
                        <?php }?>