<div class="layout-content">
    <div class="layout-content-body">
        <div class="title-bar">
            <h1 class="title-bar-title">
                <?php $controller=$this->uri->segment(1);?>
                <span class="d-ib">Technician Scopes - <?php echo $technician['name'].' ('. $technician['email'] .')'; ?></span>
            </h1>
        </div>

        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="panel panel-body" data-toggle="match-height">
                    <h5>Select Date</h5>
                    <div class="input-with-icon">
                        <form action="" method="get">
                        <input class="form-control" type="text" value="<?php if(isset($search_date)) echo $search_date;?>" name="date" data-provide="datepicker" data-date-today-highlight="true" required>
                        <span class="icon icon-calendar input-icon"></span>
                        <button type="submit" class="btn btn-primary">Get Scopes</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php if(is_array($scopes) && count($scopes) > 0) { ?>
            <div class="col-xs-6 col-md-9">
                <div class="pull-right" style="margin-top:115px;"><?php echo 'Found <strong>'.count($scopes).'</strong> scopes for <strong>'.$technician['name'].'</strong>'; ?></div>
            </div>
            <?php } ?>
            <div class="col-xs-12">
                <?php if(isset($errors)){?>
                    <div class="alert alert-danger">
                        <?php print_r($errors);?>
                    </div>
                <?php }?>
                <?php if(isset($success)){?>
                    <div class="alert alert-success">
                        <?php print_r($success);?>
                    </div>
                <?php }?>
                <div class="clearfix"></div>
                <?php if(is_array($scopes) && count($scopes) > 0) { ?>
                <div class="panel">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="demo-dynamic-tables-2" class="table table-middle nowrap">
                                <thead>
                                <tr>
                                    <th>Address</th>
                                    <th>Type</th>
                                    <th>Scope Type</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Download PDF</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php foreach($scopes as $scope){?>
                                    <tr>
                                        <td><?php echo $scope['project_address']; ?></td>
                                        <td><?php echo $scope['rtype']; ?></td>
                                        <td><?php echo $scope['scope_type']; ?></td>
                                        <td><?php echo '$'.number_format($scope['price'], 2); ?></td>
                                        <td><?php echo $scope['status']; ?></td>
                                        <td><?php echo date('D, d M Y H:i:s', strtotime($scope['date_completed'])); ?></td>
                                        <td><a href="<?php echo $scope['pdf']; ?>" target="_blank">Download PDF</a></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } else echo '<br><strong>Sorry!</strong>, no scopes found for this technician in given date.'; ?>
            </div>
        </div>
        
        </div>
    </div>
</div>
