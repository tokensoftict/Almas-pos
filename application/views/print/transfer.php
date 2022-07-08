<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo APP_NAME ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <base href="<?php echo base_url(); ?>">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i,800" rel="stylesheet">
    <link href="<?php echo base_url() ?>css/font-awesome.min.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">
    <style>
        .invoice-title h2, .invoice-title h3 {
            display: inline-block;
        }

        .table > tbody > tr > .no-line {
            border-top: none;
        }

        .table > thead > tr > .no-line {
            border-bottom: none;
        }

        .table > tbody > tr > .thick-line {
            border-top: 2px solid;
        }
    </style>
    <style>
        #background{
            position:absolute;
            z-index:0;
            display:block;
            min-height:50%;
            min-width:50%;
            color:yellow;
        }

        #content{
            position:absolute;
            z-index:1;
        }

        #bg-text
        {
            color:lightgrey;
            font-size:22px;
            transform:rotate(300deg);
            -webkit-transform:rotate(300deg);
        }
    </style>
</head>
<body  onload="window.print();"><br/><br/>
<div class="container" >
    <div class="row">
        <div class="col-xs-12">

            <div class="row">
                <div class="col-xs-6">
                    <img src="<?php echo $this->settings->getSettings()['slogo'] ?>" alt="">
                    <address>
                        <br/><?php echo $this->settings->getSettings()['saddress_1']  ?>
                        <?php
                        if(!empty($this->settings->getSettings()['saddress_2'])){
                            ?><br/><?php echo $this->settings->getSettings()['saddress_2']  ?>
                            <?php
                        }
                        ?>
                        <?php
                        if(!empty($this->settings->getSettings()['scontact_no'])){
                            ?><br/><?php echo $this->settings->getSettings()['scontact_no']  ?>
                            <?php
                        }
                        ?>
                    </address>
                </div>
            </div>
        </div>
        <div class="invoice-title">
            <h2>Transfer Printout </h2><h3 class="pull-right"> # <?php echo $transfer['transfer_id'] ?></h3>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-6">
                <address>
                    <strong>Transfer To:</strong><br>
                   Branch : <?php
                    echo $transfer['branch']
                    ?><br/>
                  Date :<?php
                    echo $transfer['transfer_date']
                    ?>
                </address>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Product(s)</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Total</th>
                            </tr>
                            </thead>
                            <tbody id="produt_list">
                            <?php
                            $products = json_decode($transfer['products'],TRUE);
                            $total = 0;
                            foreach($products as $product){
                                $prod_name =$this->stock->getStock($product['product']);
                                $total+=$product['total'];
                                ?>
                                <tr>
                                    <td><?php echo $prod_name[0]['product_name'] ?></td>
                                    <td><?php echo $product['qty'] ?></td>
                                    <td><b><?php echo number_format($product['price'],2) ?></b></td>
                                    <td><b><?php echo number_format($product['total'],2) ?></b></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td align="right"><b>Total</b></td>
                                    <td align="left"><b><?php echo number_format($total,2) ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br/><br/>
<div align="center" style="font-size:13px;"><?php
    $settings = $this->db->get_where("others",array("SN"=>"1"))->row_array();
    echo $settings['footer_rec'];
    ?></div><br/>
<?php
if(!isset($_GET['print'])){
    ?>
    <script>
        function warning(link){
            var con =confirm("Are you sure..this can not be reversed..");
            if(con== true){
                return true;
            }
            return false;
        }
    </script>
    <?php
}
?>
</body>
</html>
