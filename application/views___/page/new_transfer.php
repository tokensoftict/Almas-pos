<div class="row">
    <div class="col-sm-12">
        <form action="" method="post" id="stock_recieved_form">
            <div class="panel panel-default">
                <div class="panel-heading">New Stock Transfer

                </div>
                <div class="panel-body">
                    <div class="">
                        <div class="main-content container-fluid">
                            <div class="row wizard-row">
                                <div class="col-md-12 fuelux">
                                    <div class="block-wizard panel panel-default">
                                        <div id="wizard1" class="wizard wizard-ux">
                                            <ul class="steps">
                                                <li data-step="1" class="active">Step 1<span class="chevron"></span></li>
                                                <li data-step="2">Step 2<span class="chevron"></span></li>
                                            </ul>
                                            <div class="actions">
                                                <button type="button" class="btn btn-xs btn-prev btn-default"><i class="icon mdi mdi-chevron-left"></i>Prev</button>
                                                <button type="button" data-last="Finish" class="btn btn-xs btn-next btn-default">Next<i class="icon mdi mdi-chevron-right"></i></button>
                                            </div>
                                            <div class="step-content">
                                                <div data-step="1" class="step-pane active">
                                                    <h3>Transfer Information</h3>
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <div class="form-group xs-pt-10">
                                                                <label> Transfer Date</label>
                                                                <input id="transfer_date" required type="text" required value="<?php echo date('Y-m-d'); ?>" name="recieved_date" data-min-view="2" data-date-format="yyyy-mm-dd"  placeholder="Date Available" class="date form-control input-sm">
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-3">
                                                            <div class="form-group xs-pt-10">
                                                                <label>Transfer To</label>
                                                                <select required class="form-control input-sm" name="branch">
                                                                    <?php
                                                                    $branch = $this->db->where('delete_status','0')->get('branch')->result_array();
                                                                    foreach($branch as $br) {
                                                                        ?>
                                                                        <option><?php echo $br['branch_name'] ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <!--<input type="text" name="branch" class="form-control input-sm" placeholder="Branch Or Supplier"/>-->
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-3">
                                                            <div class="form-group xs-pt-10">
                                                                <label>Transfer User</label>
                                                                <input type="text" readonly required value="<?php  echo $this->tank_auth->get_username(); ?>" placeholder="Transfer User" class="form-control input-sm">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group xs-pt-10">
                                                                <label>Received User</label>
                                                                <input type="text" placeholder="Received User Fullname" name="reciever_userfullname" class="form-control input-sm">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group xs-pt-10">
                                                                <label>Transfer Note</label>
                                                                <textarea placeholder="Received Note" style="height:150px" class="form-control col-lg-12" name="transfer_note"></textarea>
                                                            </div>

                                                        </div>
                                                    </diV>
                                                    <div class="form-group">
                                                        <br/><br/>
                                                        <button type="button" data-wizard="#wizard1" class="btn btn-default btn-space wizard-previous">Previous</button>
                                                        <button type="button" data-wizard="#wizard1" class="btn btn-primary btn-space wizard-next">Next Step</button>

                                                    </div>


                                                </div>
                                                <div data-step="2" class="step-pane">
                                                    <h3>Product List(s)</h3>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th style="min-width:40%;">Product</th>
                                                            <th style="width:25%;">Quantity</th>
                                                            <th style="width:5%;">Price</th>
                                                            <th style="width:5%;">Total</th>
                                                            <th style="width:5%;">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="produt_list">
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th id="alltotal"><?php echo number_format(0,2) ?></th>

                                                            <th>
                                                                <button onclick="addTemp(this);" type="button" class="btn btn-sm btn-primary">Add Product</button>

                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>

                                                    <div class="form-group">
                                                        <br/><br/>
                                                        <button data-wizard="#wizard1" type="button" class="btn btn-default btn-space wizard-previous">Previous</button>
                                                        <button data-wizard="#wizard1" class="btn btn-success btn-space" onclick="$(this).attr('disabled','disabled'); return $('#stock_recieved_form').submit();" type="button"><i class="mdi mdi-plus"></i> Complete</button>

                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">

                </div>

            </div>
        </form>
    </div>

    <script>
        function handle_change(){
            $('.handle_change').off('change');
            $('.handle_change').on('change',function(){
                var parent = $(this).parent().parent();
                var price = $('option:selected', this).attr('price');
                parent.find('.price').html(parseFloat(price).toFixed(2)).attr('price',price);
                parent.find('.total').html(parseInt(0).toFixed(2));
            });

            $('.qty').off('keyup');
            $('.qty').on('keyup',function(){
                var parent = $(this).parent().parent().parent();
                var price =  parent.find('.price').attr('price');
                var total = parent.find('.total');
                var qty = $(this).val() == "" ? 0 : $(this).val();
                var _total = parseInt(qty) * price;
                total.html(_total.toFixed(2)).attr('total',_total);
                var tt = 0;
                $('.total').each(function(i,elem){
                    if($(elem).attr('total')){
                        tt += parseFloat($(elem).attr('total'));
                    }
                    $('#alltotal').html(tt.toFixed(2));
                })

            })

        }
    </script>
