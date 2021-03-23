<style type="text/css">
    .form_group input {
    border: 1px solid #000;
    padding: 15px;
    width: 100%;
    margin-top: 10px;
    margin-bottom: 10px;
}
</style>

<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<?php
$encomendas_id = $row_rsEncomenda['id'];
$clienteid = $row_rsEncomenda["id_cliente"];
$pp_check = $row_rsEncomenda['valor_c_iva'];

$query_rsQtds = "SELECT * FROM clientes WHERE id = $clienteid";
$rsQtds = DB::getInstance()->prepare($query_rsQtds);
$rsQtds->execute();
$row_rsQtds = $rsQtds->fetch(PDO::FETCH_ASSOC);
$totalRows_rsQtds = $rsQtds->rowCount();

    //include("header.php");
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $redirect_url = $protocol . $_SERVER['HTTP_HOST'] . "/apm";
 ?>

<?php
    
    $query_rsP = "SELECT * FROM met_pagamento_en WHERE id=10";

    $rsP = DB::getInstance()->prepare($query_rsP);   

    $rsP->execute();

    $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

    $totalRows_rsP = $rsP->rowCount();


?>
        <script src="https://cdn.worldpay.com/v1/worldpay.js"></script>
       <!--  <h1>PHP Library Create Order Example</h1> -->
    <?php if($txn_id == "") {  ?>
        <form method="post" action="includes/worldpay/create_order.php" id="my-payment-form">
            <div class="payment-errors"></div>
          <!--   <div class="header">Checkout</div>
 -->
           <!--  <div class="form-row">
                <label>Direct Order?</label>
                 <select id="direct-order" name="direct-order">
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </div> -->
            <input type="1" id="direct-order" name="direct-order">
<!-- 
           <div class="form-row"> -->
              <!--   <label>Order Type</label> -->
          <!--        <select id="order-type" name="order-type">
                    <option value="ECOM" selected>ECOM</option>
                    <option value="MOTO">MOTO</option>
                    <option value="RECURRING">RECURRING</option>
                    <option value="APM">APM</option>
                </select>
            </div> -->

            <input type="hidden" value="ECOM" id="order-type" name="order-type">

            <input type="hidden" id="apm-name" data-worldpay="apm-name" value="paypal">
          <!--   <div class="form-row apm" style="display:none;">
                <select id="apm-name" data-worldpay="apm-name">
                    <option value="paypal" selected="selected">PayPal</option><option value="giropay">Giropay</option><option value="ideal">iDEAL</option>
                </select>
            </div> -->

            <div class="form-row no-apm">
               <!--  <label>Site Code</label> -->
                <input type="hidden" id="site-code" name="site-code" value="N/A" />
            </div>

            <div class="form-row">
                <input type="hidden" id="name" name="name" data-worldpay="name" value="<?php echo $row_rsQtds["nome"]; ?>" />
            </div>

             <div class="form-row apm apm-url" style="display:none;">
               <!--  <label>
                    Success URL
                </label> -->
                <input type="hidden" id="success-url" name="success-url" placeholder="<?php echo $redirect_url . '/success.php';?>"/>
            </div>

             <div class="form-row apm apm-url" style="display:none;">
             <!--    <label>
                    Cancel URL
                </label> -->
                <input type="hidden" id="cancel-url" name="cancel-url" placeholder="<?php echo $redirect_url . '/cancel.php';?>"/>
            </div>

             <div class="form-row apm apm-url" style="display:none;">
             <!--    <label>
                    Failure URL
                </label> -->
                <input type="hidden" id="failure-url" name="failure-url" placeholder="<?php echo $redirect_url . '/error.php';?>"/>
            </div>

             <div class="form-row apm apm-url" style="display:none;">
              <!--   <label>
                    Pending URL
                </label> -->
                <input type="hidden" id="pending-url" name="pending-url" placeholder="<?php echo $redirect_url . '/pending.php';?>"/>
            </div>

            <div class="form_group">
                <label>
                    Card Number
                </label>
                <input type="text" id="card" size="20" data-worldpay="number" value="" />

            </div>



            <div class="form-row no-apm form_group">
                <label>
                    CVC
                </label>
                <input type="text" id="cvc" size="4" data-worldpay="cvc" value="321" />
            </div>


            <div class="form-row no-apm form_group">
                <label>
                    Expiration (MM/YYYY)
                </label>
                <select id="expiration-month" data-worldpay="exp-month">
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
                <span> / </span>
                <select id="expiration-year" data-worldpay="exp-year">
                    <?php 
                        for ($i = date('Y'); $i <= date('Y')+10; $i++) {
                            echo '<option value="'.$i.'">'.$i.'</option>';
                    } ?>
                </select>
            </div>

            <div class="form-row">
            <!--     <label>
                    Amount
                </label> -->
                <input type="hidden" id="amount" size="4" name="amount" value="<?php echo $pp_check; ?>" />
                 <input type="hidden" id="encomendas_id" name="encomendas_id" data-worldpay="encomendas_id" value="<?php echo $encomendas_id; ?>" />
            </div>

            <div class="form-row">
           <!--      <label>
                    Currency
                </label> -->
                <input type="hidden" id="currency" name="currency" value="GBP" />
            </div>

            <input type="hidden" id="settlement-currency" name="settlement-currency" value="GBP">
            <!-- <div class="form-row">
                <label>Settlement Currency</label>
                 <select id="settlement-currency" name="settlement-currency">
                    <option value="" selected></option>
                    <option value="USD">USD</option>
                    <option value="GBP">GBP</option>
                    <option value="EUR">EUR</option>
                    <option value="CAD">CAD</option>
                    <option value="NOK">NOK</option>
                    <option value="SEK">SEK</option>
                    <option value="SGD">SGD</option>
                    <option value="HKD">HKD</option>
                    <option value="DKK">DKK</option>
                </select>
            </div> -->

            <div class="form-row reusable-token-row">
             <!--    <label>Reusable Token</label> -->
                <input type="checkbox" id="chkReusable" name="chkReusable"/>
            </div>

            <div class="form-row no-apm">
            <!--     <label>Use 3DS</label> -->
                <input type="checkbox" id="chk3Ds" name="3ds" />
            </div>

            <div class="form-row no-apm">
               <!--  <label>Authorize Only</label> -->
                <input type="checkbox" id="chkAuthorizeOnly" name="authorizeOnly" />
            </div>

           <!--  <div class="header">Billing address</div> -->
            <div class="form-row">
                <!-- <label>
                    Address 1
                </label> -->
                <input type="hidden" id="address1" name="address1" value="<?php echo $row_rsQtds["morada"]; ?>" />
            </div>

            <div class="form-row">
                <!-- <label>
                    Address 2
                </label>
 -->                <input type="hidden" id="address2" name="address2" value="<?php echo $row_rsQtds["localidade"]; ?>" />
            </div>

            <div class="form-row">
              <!--   <label>
                    Address 3
                </label> -->
                <input type="hidden" id="address3" name="address3" value="" />
            </div>

            <div class="form-row">
               <!--  <label>
                    City
                </label> -->
                <input type="hidden" id="city" name="city" value="<?php echo $row_rsQtds["pais"]; ?>" />
            </div>

            <div class="form-row">
               <!--  <label>
                    State
                </label> -->
                <input type="hidden" id="state" name="state" value="" />
            </div>

            <div class="form-row">
              <!--   <label>
                    Postcode
                </label> -->
                <input type="hidden" id="postcode" name="postcode" value="<?php echo $row_rsQtds["cod_postal"]; ?>" />
            </div>

            <div class="form-row">
               <!--  <label>
                    Country Code
                </label> -->
                <input type="hidden" id="country-code" name="countryCode" value="GB" />
            </div>

            <div class="form-row">
               <!--  <label>
                    Telephone Number
                </label> -->
                <input type="hidden" id="telephone-number" name="telephoneNumber" />
            </div>

           <!--  <div class="header">Delivery address</div> -->
            <div class="form-row">
                <!-- <label>
                    First Name
                </label> -->
                <input type="hidden" id="delivery-first-name" name="delivery-firstName" value="John" />
            </div>
            <div class="form-row">
               <!--  <label>
                    Last Name
                </label> -->
                <input type="hidden" id="delivery-last-name" name="delivery-lastName" value="Doe" />
            </div>
            <div class="form-row">
               <!--  <label>
                    Address 1
                </label> -->
                <input type="hidden" id="delivery-address1" name="delivery-address1" value="123 House Road" />
            </div>

            <div class="form-row">
               <!--  <label>
                    Address 2
                </label> -->
                <input type="hidden" id="delivery-address2" name="delivery-address2" value="A village" />
            </div>

            <div class="form-row">
                <!-- <label>
                    Address 3
                </label> -->
                <input type="hidden" id="delivery-address3" name="delivery-address3" value="" />
            </div>

            <div class="form-row">
             <!--    <label>
                    City
                </label> -->
                <input type="hidden" id="delivery-city" name="delivery-city" value="London" />
            </div>


            <div class="form-row">
             <!--    <label>
                    State
                </label> -->
                <input type="hidden" id="delivery-state" name="delivery-state" value="London" />
            </div>

            <div class="form-row">
              <!--   <label>
                    Postcode
                </label> -->
                <input type="hidden" id="delivery-postcode" name="delivery-postcode" value="EC1 1AA" />
            </div>

            <div class="form-row">
               <!--  <label>
                    Country Code
                </label> -->
                <input type="hidden" id="delivery-country-code" name="delivery-countryCode" value="GB" />
            </div>

            <div class="form-row">
               <!--  <label>
                    Telephone Number
                </label> -->
                <input type="hidden" id="delivery-telephone-number" name="delivery-telephoneNumber" />
            </div>

            <!-- <div class="header">Other</div> -->

            <div class="form-row">
             <!--    <label>
                    Order Description
                </label> -->
                <input type="hidden" id="description" name="description" value="<?php echo $encomendas_id; ?>" />
            </div>

            <div class="form-row">
               <!--  <label>
                    Statement Narrative
                </label> -->
                <input type="hidden" id="statement-narrative" maxlength="24" name="statement-narrative" value="Statement Narrative" />
            </div>

            <div class="form-row">
             <!--    <label>
                    Customer Order Code
                </label> -->
                <input type="hidden" id="customer-order-code" name="customer-order-code" value="A123" />
            </div>

            <div class="form-row">
              <!--   <label>
                    Order Code Prefix
                </label> -->
                <input type="hidden" id="code-prefix" name="code-prefix" value="" />
            </div>

            <div class="form-row">
               <!--  <label>
                    Order Code Suffix
                </label> -->
                <input type="hidden" id="code-suffix" name="code-suffix" value="" />
            </div>

            <div class="form-row language-code-row">
               <!--  <label>Shopper Language Code</label> -->
                <input type="hidden" id="language-code" maxlength="2" data-worldpay="language-code" value="EN" />
            </div>

            <div class="form-row">
             <!--    <label>Shopper Email</label> -->
                <input type="hidden" id="shopper-email" name="shopper-email" value="shopper@email.com" />
            </div>

            <div class="form-row swift-code-row apm" style="display:none">
             <!--    <label>
                    Swift Code
                </label> -->
                <input type="hidden" id="swift-code" value="NWBKGB21" />
            </div>

            <div class="form-row shopper-bank-code-row apm" style="display:none">
              <!--   <label>
                    Shopper Bank Code
                </label> -->
                <input type="hidden" id="shopper-bank-code" value="RABOBANK" />
            </div>

            <div class="form-row large">
                <!-- <label class='left'>
                    Customer Identifiers (json)
                </label> -->
                <textarea id="customer-identifiers" rows="6" cols="30" name="customer-identifiers"></textarea>
            </div>

            <input type="submit" id="place-order" value="Place Order" />
            </div>

            <div class="token"></div>

        </form>
    <?php } ?>

    </div>

    <script type="text/javascript">

        var showShopperBankCodeField = function() {
            $('#shopper-bank-code').attr('data-worldpay-apm', 'shopperBankCode');
            $('.shopper-bank-code-row').show();
        };
        var hideShopperBankCodeField = function() {
            $('#shopper-bank-code').removeAttr('data-worldpay-apm');
            $('.shopper-bank-code-row').hide();
        };

        var showSwiftCodeField = function() {
            $('#swift-code').attr('data-worldpay-apm', 'swiftCode');
            $('.swift-code-row').show();
        }

        var hideSwiftCodeField = function() {
            $('#swift-code').removeAttr('data-worldpay-apm');
            $('.swift-code-row').hide();
        }

        var showLanguageCodeField = function() {
            $('#language-code').attr('data-worldpay', 'language-code');
            $('.language-code-row').show();
        }

        var hideLanguageCodeField = function() {
            $('#language-code').removeAttr('data-worldpay');
            $('.language-code-row').hide();
        }

        var showReusableTokenField = function() {
            $('.reusable-token-row').show();
        }

        var hideReusableTokenField = function() {
            $('.reusable-token-row').hide();
        }


        if (!window['Worldpay']) {
            document.getElementById('place-order').disabled = true;
        }
        else {


            // Set client key
            Worldpay.setClientKey("<?php echo $row_rsP["client_key"]; ?>");
            // Get form element

            var form = $('#my-payment-form')[0];
            var _triggerWorldpayUseForm = function() {
                Worldpay.useForm(form, function (status, response) {
                    if (response.error) {
                        Worldpay.handleError(form, $('#my-payment-form .payment-errors')[0], response.error);
                    } else if (status != 200) {
                        Worldpay.handleError(form, $('#my-payment-form .payment-errors')[0], response);
                    } else {
                        var token = response.token;
                        Worldpay.formBuilder(form, 'input', 'hidden', 'token', token);
                        $('#my-payment-form .token').html("Your token is: " + token);
                        form.submit();
                    }
                });
            };
            _triggerWorldpayUseForm();

            $('#chkReusable').change(function(){
                if ($(this).is(':checked')) {
                    Worldpay.reusable = true;
                }
                else {
                    Worldpay.reusable = false;
                }
            });

            $('#direct-order').on('change', function() {
                var isDirectOrder = $(this).val();
                if (isDirectOrder == 1) {
                    form.onsubmit = null;

                    //add names to card form parameters
                    $('#card').attr('name', 'card');
                    $('#cvc').attr('name', 'cvc');
                    $('#expiration-month').attr('name', 'expiration-month');
                    $('#expiration-year').attr('name', 'expiration-year');
                    $('#apm-name').attr('name', 'apm-name');
                    $('#swift-code').attr('name','swiftCode');
                    $('#shopper-bank-code').attr('name','shopperBankCode');
                    $('#language-code').attr('name','language-code');
                }
                else {
                    $('#card, #cvc, #expiration-month, #expiration-year, #apm-name, #swiftCode, #shopperBankCode, #language-code').removeAttr('name');
                    _triggerWorldpayUseForm();
                }
            });

            $('#order-type').on('change', function () {
                if ($(this).val() == 'APM') {
                    Worldpay.tokenType = 'apm';
                    $('.apm').show();
                    $('.no-apm').hide();

                    //initialize fields
                    hideShopperBankCodeField();
                    hideSwiftCodeField();
                    showReusableTokenField();
                    showLanguageCodeField();

                    //handle attributes
                    $('#card').removeAttr('data-worldpay');
                    $('#cvc').removeAttr('data-worldpay');
                    $('#expiration-month').removeAttr('data-worldpay');
                    $('#expiration-year').removeAttr('data-worldpay');
                    $('#country-code').attr('data-worldpay', 'country-code');
                } else {
                    Worldpay.tokenType = 'card';
                    $('.apm').hide();
                    $('.no-apm').show();
                    $('#card').attr('data-worldpay', 'number');
                    $('#cvc').attr('data-worldpay', 'cvc');
                    $('#expiration-month').attr('data-worldpay', 'exp-month');
                    $('#expiration-year').attr('data-worldpay', 'exp-year');
                    $('#country-code').removeAttr('data-worldpay');
                }
            });

            $('#apm-name').on('change', function () {
                var _apmName = $(this).val();

                hideSwiftCodeField();
                hideShopperBankCodeField();
                hideLanguageCodeField();
                hideReusableTokenField();

                $('#country-code').val('GB');
                $('#currency').val('GBP');

                switch (_apmName) {
                    case 'mistercash':
                        showReusableTokenField();
                        showLanguageCodeField();
                        $('#country-code').val('BE');
                    break;
                    case 'yandex':
                    case 'qiwi':
                        showReusableTokenField();
                        showLanguageCodeField();
                        $('#country-code').val('RU');
                    break;
                    case 'postepay':
                        showReusableTokenField();
                        showLanguageCodeField();
                        $('#country-code').val('IT');
                    break;
                    case 'alipay':
                        showReusableTokenField();
                        showLanguageCodeField();
                        $('#country-code').val('CN');
                    break;
                    case 'przelewy24':
                        showReusableTokenField();
                        showLanguageCodeField();
                        $('#country-code').val('PL');
                    break;
                    case 'sofort':
                        showReusableTokenField();
                        showLanguageCodeField();
                        $('#country-code').val('DE');
                    break;
                    case 'giropay':
                        Worldpay.reusable = false;
                        showSwiftCodeField();
                        $('#currency').val('EUR');
                    break;
                    case 'ideal':
                        //reusable token field is available for all apms (except giropay)
                        showReusableTokenField();
                         //language code enabled for all apms (except giropay)
                        showLanguageCodeField();
                         //shopper bank code field is only available for ideal
                        showShopperBankCodeField();
                    break;
                    default:
                        showReusableTokenField();
                        showLanguageCodeField();
                    break;
                }

            });
        }
        $('#chkReusable').prop('checked', false);
    </script>

</body>
</html>
