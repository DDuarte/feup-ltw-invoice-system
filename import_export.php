<?php

require 'api/details/user_management.php';
redirect_if_not_logged_in();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Online Invoicing System</title>
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery.xml2json.js"></script>
    <script src="js/index.js"></script>
    <script src="js/importExternalDb.js"></script>
    <link rel="stylesheet"  href="css/search.css" type="text/css">
    <script type="text/javascript">
        $(document).ready( function() {
            load('import_export');
            loadExt();
        });
    </script>
</head>
<body>
<div id="pageHeader"></div>
<div class="search_results">
    <?php if (is_editor()) { ?>
    <h3>Import SAFT-PT</h3>
    <img src="images/glyphicons_358_file_import.png" title="import" width="20" height="20">
    <input type="file" id="files" name="files[]" accept="text/xml" />
    <script>
        if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
            alert('The File APIs are not fully supported in this browser.');
        }

        function send_post(url, name, array) {
            var data = {};
            data[name] = JSON.stringify(array);
            $.ajax({
                type: 'POST',
                url: url,
                data: data
            });
        }

        function send_post_sync(url, name, array) {
            var data = {};
            data[name] = JSON.stringify(array);
            var ret = null;
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                async: false
            }).done(function(msg) {
                    ret = msg;
                });
            return ret;
        }

        function handleFileSelect(evt) {
            var files = evt.target.files;
            if (files.length == 1) {
                var reader = new FileReader();
                reader.onload = (function(file) {
                    var xmlStr = reader.result.toString();
                    var json = $.xml2json(xmlStr);

                    var taxes = json['MasterFiles']['TaxTable']['TaxTableEntry'];
                    var customers = json['MasterFiles']['Customer'];
                    var invoices = json['SourceDocuments']['SalesInvoices']['Invoice'];
                    var products = json['MasterFiles']['Product'];

                    for (var i = 0; i < invoices.length; ++i) {
                        if (!$.isArray(invoices[i]['Line'])) {
                            invoices[i]['Line'] = [invoices[i]['Line']];
                        }
                    }

                    for (var i = 0; i < taxes.length; ++i)
                        send_post('api/updateTax.php', 'tax', taxes[i]);

                    for (var i = 0; i < customers.length; ++i) {
                        var oldId = customers[i]['CustomerID'];
                        customers[i]['CustomerID'] = '';
                        var newId = send_post_sync('api/updateCustomer.php', 'customer', customers[i]);
                        for (var j = 0; j < invoices.length; ++j) {
                            if (invoices[j]['CustomerID'] === oldId)
                                invoices[j]['CustomerID'] = newId;
                        }
                    }

                    for (var i = 0; i < products.length; ++i) {
                        var oldCode = products[i]['ProductCode'];
                        products[i]['ProductCode'] = '';
                        var newCode = send_post_sync('api/updateProduct.php', 'product', products[i]);
                        for (var j = 0; j < invoices.length; ++j) {
                            for (var k = 0; k < invoices[j]['Line'].length; ++k) {
                                if (invoices[j]['Line'][k]['ProductCode'] === oldCode)
                                    invoices[j]['Line'][k]['ProductCode'] = newCode;
                            }
                        }
                    }

                    for (var i = 0; i < invoices.length; ++i) {
                        invoices[i]['InvoiceNo'] = '';
                        send_post('api/updateInvoice.php', 'invoice', invoices[i]);
                    }

                    alert("Import complete!");
                });
                reader.readAsText(files[0]);
            }
        }

        document.getElementById('files').addEventListener('change', handleFileSelect, false);
    </script><br/><br/>
    <?php } ?>
    <h3>Export SAFT-PT</h3>
    <label for="startDate">Start date (optional): </label><input type="date" id="startDate" name="startDate"><br/>
    <label for="endDate">End date (optional): </label><input type="date" id="endDate" name="endDate"><br/>
    <a href="exportSAFT.php" id="exportSAFT" target="_blank"><img src="images/glyphicons_359_file_export.png" title="export" width="20" height="20"></a>
    <script>
        $(function(){
            $("#exportSAFT").click(function(){
                var original_url = this.href,
                    url      = "exportSAFT.php";

                var data = {};
                if ($('#startDate').val())
                    data['startDate'] = $('#startDate').val();
                if ($('#endDate').val())
                    data['endDate'] = $('#endDate').val();

                $.ajax({
                    url     : url,
                    type    : 'get',
                    data    : data,
                    success : function (serverResponse) {
                        window.location = original_url + '?' + $.param(data);
                    },
                    error   : function () {
                        window.location = original_url;
                    }
                });
                return false;
            });
        });
    </script>
    <?php if (is_editor()) { ?>
    <br/><br/>
    <h3>Import External Website</h3>
    <form id="import_url_form">
        <img src="images/glyphicons_364_cloud_download.png" title="import from url" width="20" height="20">
        <input type="url" placeholder="url..." id="url_field" required>
        <input type="submit" value="Import" id="input_button">
    </form><br/><br/>
    <?php } ?>
    <?php if (is_editor()) { ?>
    <h3>Delete Data</h3>
    <a href="api/deleteAll.php" id="deleteAll"><img src="images/glyphicons_143_database_ban.png" title="delete" width="20" height="20"></a>
    <script>
        $(function(){
            $("#deleteAll").click(function(){
                var r = confirm("Are you sure you want to wipe EVERYTHING?");
                if (r === false)
                    return false;

                $.ajax({
                    url: 'api/deleteAll.php',
                    success: function (resp) {
                        //var resp = JSON.parse(response);
                        alert('Successfully deleted '
                            + resp.CustomersDeleted + ' customers, '
                            + resp.InvoicesDeleted + ' invoices, '
                            + resp.LinesDeleted + ' invoices\' lines, '
                            + resp.TaxesDeleted + ' taxes and '
                            + resp.ProductsDeleted + ' products.');
                    },
                    error: function () {
                        alert('Failed to delete.');
                    }
                });
                return false;
            });
        });
    </script>
    <?php } ?>
</div>
</body>
</html>
