<?php

require_once 'api/details/user_management.php';
redirect_if_not_logged_in();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Online Invoicing System</title>
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/index.js"></script>
        <link rel="stylesheet"  href="css/index.css" type="text/css">
    </head>
    <body onload="load('about')">
            <div id="pageHeader"></div>
            <div class="_info_block">
                <h1>Group: <small>T1G01</small></h1>

                <h2 class="_sub_title"> Group elements </h2>
                <div class="_text_block _no_margin">
                <ul class="_names_list" value="disc">
                    <li>
                        Duarte Nuno Pereira Duarte - <a href="mailto:ei11101@fe.up.pt">ei11101@fe.up.pt</a>
                    </li>
                    <li>
                        Eduardo José Valadar Martins - <a href="mailto:ei11104@fe.up.pt">ei11104@fe.up.pt</a>
                    </li>
                    <li>
                        Miguel Rui Pereira Marques - <a href="mailto:ei11099@fe.up.pt">ei11099@fe.up.pt</a>
                    </li>
                    <li>
                        Ruben Fernando Pinto Cordeiro - <a href="mailto:ei11097@fe.up.pt">ei11097@fe.up.pt</a>
                    </li>
                </ul>
                </div>
                <h2 class="_sub_title">Project description</h2>
                <div class="_text_block">
                    <p>
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspThis project consists in the creation of a <i>sqlite</i> document database that allows <strong>SCRUD</strong> (Search Create Read Update Delete) operations through an <strong>API</strong>.
                    </p>

                    <p>
                    Since the project is at the first stage of development, a user can only search for documents in the database. No user permissions are taken into account.<br>

                    The search <strong>API</strong> was fully implemented, according to the project specification.
                    </p>
                </div>

                <h2 class="_sub_title">Usability</h2>
                <div class="_text_block">
                    At this stage of development, a user can:
                    <ol class="_usability_list">
                        <li> Search for summarized documents, mainly invoices, costumers and products;</li>
                        <li> Access detailed information about each document as a search result;</li>
                        <li> Print a given invoice.</li>
                    </ol>
                </div>
                <h2 class="_sub_title">Notes</h2>
                <div class="_text_block">
                    <p>
                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspThe index html page is fully dynamic, using successive Ajax requests to the search API in order to generate the search results.
                    </p>
                </div>
            </div>

    </body>
</html>