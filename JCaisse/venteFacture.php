<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/

session_start();

date_default_timezone_set('Africa/Dakar');

if(!$_SESSION['iduser']){
	header('Location:../JCaisse/index.php');
}
require('connection.php');

require('connectionPDO.php');

require('declarationVariables.php');

$idPagnet=@$_GET["id"];
$client_Facture=@$_GET["client"];
$adresse_Facture=@$_GET["adresse"];
$telephone_Facture=@$_GET["telephone"];


?>
<html>
    <head>
        <meta charset="utf-8">
        <script src="js/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="js/pdfmake.min.js"></script>
        <script type="text/javascript" src="js/vfs_fonts.js"></script>
    </head>
    <body>
        <span style="display:none" id="spn_Image"></span> 
        <span style="display:none" id="spn_Barcode"></span> 
    </body>    
    <script>
      $(document).ready(function () {

        idPagnet = <?php echo json_encode($idPagnet); ?>;
        client_Facture = <?php echo json_encode($client_Facture); ?>;
        adresse_Facture = <?php echo json_encode($adresse_Facture); ?>;
        telephone_Facture = <?php echo json_encode($telephone_Facture); ?>;

        nom_Boutique = <?php echo json_encode(strtoupper($_SESSION['labelB'])); ?>;
        description_Boutique = <?php echo json_encode(strtolower($_SESSION['descriptionB'])); ?>;
        adresse_Boutique = <?php echo json_encode(strtoupper($_SESSION['adresseB'])); ?>; 
        telephone_Boutique = <?php echo json_encode($_SESSION['telBoutique']); ?>;
        registre_commerce = 'R.C. : '+<?php echo json_encode(strtoupper($_SESSION['RegistreCom'])); ?>;
        ninea = 'NINEA : '+<?php echo json_encode(strtoupper($_SESSION['Ninea'])); ?>;
        image_Boutique = <?php echo json_encode($_SESSION['imageB']); ?>;

        infos_Qr = 'Cette facture est produite avec le Logiciel JCaise. \n'+
                   'Pour plus informations, veuillez contacter le service commercial : \n'+
                   '+ Telephone : 77 477 98 98 \n+ Email : contact@yaatout.org';
                   
        //alert(registre_commerce)
            //var url_image = 'images/logo_Yaatout.png';
            //var url_image = 'images/pharmacie.png';
            //var url_image = 'images/logo_Diallo_1.png';
            if(image_Boutique!='' && image_Boutique!=null){
                var url_image = './logo/'+image_Boutique;
            }
            else{
                var url_image = './logo/shop.ico';
            }
        //var url_image = 'images/logo_Yaatout_1.png';
        function toDataURL(src, callback){
            var image = new Image();
            image.crossOrigin = 'Anonymous';
            image.onload = function(){
                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                canvas.height = this.naturalHeight;
                canvas.width = this.naturalWidth;
                context.drawImage(this, 0, 0);
                var dataURL = canvas.toDataURL('image/png');
                callback(dataURL);
            };
            image.src = src;
        }
        toDataURL(url_image, function(dataURL){ $('#spn_Image').text(dataURL);})

        var type='code128';
        var orientation='horizontal';
        var size='42';
        var print='false';
        url_barcode='barcode128.php?text='+idPagnet+'&codetype='+type+'&orientation='+orientation+'&size='+size+'&print='+print;
        toDataURL(url_barcode, function(dataURL){ $('#spn_Barcode').text(dataURL);})

        $.ajax({
            url:"calculs/ventesBons.php",
            method:"POST",
            data:{
                operation:'detailsPanier',
                idPagnet : idPagnet,
            },
            success: function (data) {
              val_image=$('#spn_Image').text();
              val_barcode=$('#spn_Barcode').text();

              var lignes = JSON.parse(data);
              client = lignes[1];
              if(client!=0){
                var nom=lignes[1].split('<>')[0];
                var adresse=lignes[1].split('<>')[1];
                var telephone=lignes[1].split('<>')[2];
                var nom_client=nom+' \n  '+adresse+' \n  '+telephone;
              }
              else { 
                var nom_client=client_Facture+' \n  '+adresse_Facture+' \n  '+telephone_Facture; 
              }

              var heure_date=lignes[3]+' '+lignes[2];
              var montant_total = lignes[4]+' F CFA';
              var montant_remise = lignes[5]+' F CFA';
              var montant_apayer = lignes[6]+' F CFA';
              var type_compte = lignes[7].split('.')[1];
              var montant_especes = lignes[8]+' F CFA';
              var montant_rendu = lignes[9]+' F CFA';

              var type_facture='Facture';
              if(lignes[11]==10){
                var type_facture='Proforma';
              }
        
              data = [];
              function setColumns(data) {
                  let tableData = [];

                  var taille = lignes[10].length;
                  var n=1;
                  for(var i = 0; i<taille; i++){
/*                     tableData.push([
                              { text: lignes[10][i][1],fontSize: 11 },
                              { text: lignes[10][i][2],fontSize: 11  },
                              { text: lignes[10][i][4],fontSize: 11  },
                              { text: lignes[10][i][5],fontSize: 11  },
                          ]); */

/*                           if(n==21){
                            for(var j = 0; j<1; j++){
                                tableData.push([
                                    {text: ' ', border: [false, false, false, false], margin: [0, 7, 0, 7], alignment: 'center', fontSize: 10},
                                    {text: ' ', border: [false, false, false, false], margin: [0, 7, 0, 7], alignment: 'left', fontSize: 10},
                                    {text: ' ', border: [false, false, false, false], margin: [0, 7, 0, 7], alignment: 'center', fontSize: 10},
                                    {text: ' ', border: [false, false, false, false], margin: [0, 7, 0, 7], alignment: 'center', fontSize: 10},
                                    {text: ' ', border: [false, false, false, false], margin: [0, 7, 0, 7], alignment: 'center', fontSize: 10},
                                ]);
                            }
                            n++;
                            tableData.push([
                              {text: n, border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[10][i][1].toLowerCase()+" ("+lignes[10][i][3]+")" , border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'left', fontSize: 10},
                              {text: lignes[10][i][2], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[10][i][4], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[10][i][5], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                          ]);
                        
                          }
                          else {
                            tableData.push([
                              {text: n, border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[10][i][1].toLowerCase()+" ("+lignes[10][i][3]+")" , border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'left', fontSize: 10},
                              {text: lignes[10][i][2], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[10][i][4], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[10][i][5], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                          ]);

                          n++;
                          } */
                          tableData.push([
                              {text: n, border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[10][i][1].toLowerCase()+" ("+lignes[10][i][3]+")" , border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'left', fontSize: 10},
                              {text: lignes[10][i][2], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[10][i][4], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[10][i][5], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                          ]);

                          n++;
                  } 
                  return tableData;
              }

              let columns = setColumns(data);
              
              var docDefinition = {
                    footer: {
                            columns: [
                                {
                                    alignment: 'center',
                                    text: 'MERCI DE VOTRE VISITE ET A BIENTOT',
                                    fontSize: 13,
                                    italics: true,
                                },
                            ],
                            margin: [10, 10, 10, 10]
                        },
                    content: [
                        {
                            layout: {
                                defaultBorder: false,
                                hLineWidth: function(i, node) {
                                return 1;
                                },
                                vLineWidth: function(i, node) {
                                return 1;
                                },
                                hLineColor: function(i, node) {
                                if (i === 1 || i === 0) {
                                    return 'black';
                                }
                                return 'black';
                                },
                                vLineColor: function(i, node) {
                                return 'black';
                                },
                                hLineStyle: function(i, node) {
                                return null;
                                },
                                 paddingLeft: function(i, node) {
                                return 1;
                                },
                                paddingRight: function(i, node) {
                                return 1;
                                },
                                paddingTop: function(i, node) {
                                return 2;
                                },
                                paddingBottom: function(i, node) {
                                return 2;
                                },
                                fillColor: function(rowIndex, node, columnIndex) {
                                return '#fff';
                                },
                            },
                            table: {
                                headerRows: 1,
                                widths: ['15%', '35%',  '50%'],
                                body: [
                                    [
                                        {
                                            image: val_image,
                                            width: 80,
                                            height: 65,
                                            alignment: 'center',
                                            //border: [true, true, true, true],
                                        },
                                        {
                                            //border: [true, true, true, true],
                                            stack: [

                                                    {
                                                        columns: [
                                                            {
                                                                text: nom_Boutique,
                                                                color: '#333333',
                                                                //width: '*',
                                                                fontSize: 15,
                                                                bold: true,
                                                                alignment: 'center',
                                                                margin: [0, 0, 0, 0],
                                                            },
                                                        ],
                                                    },
                                                    {
                                                        columns: [
                                                        {
                                                            text: description_Boutique,
                                                            color: 'black',
                                                            bold: true,
                                                            width: '*',
                                                            margin: [0, 0, 0, 5],
                                                            fontSize: 8,
                                                            italics: true,
                                                            alignment: 'center',
                                                        },
                                                        ],
                                                    },

                                                    ],
                                        },
                                        {
                                            //border: [true, true, true, true],
                                            stack: [
                                                    {
                                                        columns: [
                                                            {
                                                                text: adresse_Boutique,
                                                                color: 'black',
                                                                bold: true,
                                                                width: '*',
                                                                fontSize: 12,
                                                                alignment: 'right',
                                                            },
                                                        ],
                                                    },
                                                    {
                                                        columns: [
                                                        {
                                                            text: telephone_Boutique,
                                                            color: 'black',
                                                            bold: true,
                                                            width: '*',
                                                            fontSize: 12,
                                                            alignment: 'right',
                                                        },
                                                        ],
                                                    },
                                                    {
                                                        columns: [
                                                        {
                                                            text: registre_commerce,
                                                            color: 'black',
                                                            bold: true,
                                                            fontSize: 12,
                                                            alignment: 'right',
                                                            width: '*',
                                                        },
                                                        ],
                                                    },
                                                    {
                                                        columns: [
                                                        {
                                                            text: 'NINEA : 002258053',
                                                            color: 'black',
                                                            bold: true,
                                                            fontSize: 12,
                                                            alignment: 'right',
                                                            width: '*',
                                                        },
                                                        ],
                                                    },
                                                ],
                                        },
                                    ],
                                ],
                            },
                        },
                        {canvas: [{ type: 'line', x1: 0, y1: 5, x2: 595-2*40, y2: 5, lineWidth: 1 }]},
                        {
                            columns: [
                                {
                                    text: 'Client',
                                    decoration: 'underline', 
                                    decorationColor: 'black',
                                    color: 'black',
                                    bold: true,
                                    fontSize: 15,
                                    alignment: 'left',
                                    margin: [0, 5, 0, 5],
                                },
                                {
                                    text: type_facture,
                                    decoration: 'underline', 
                                    decorationColor: 'black',
                                    color: 'black',
                                    bold: true,
                                    fontSize: 15,
                                    alignment: 'right',
                                    margin: [0, 5, 0, 5],
                                },
                            ],
                        },
                        {
                        columns: [
                            {
                            text: nom_client,
                            bold: true,
                            color: '#333333',
                            alignment: 'left',
                            },
                            {
                            stack: [
                                {
                                    columns: [
                                        {
                                            text: 'Numero : ',
                                            color: 'black',
                                            bold: true,
                                            width: '*',
                                            fontSize: 12,
                                            alignment: 'right',
                                        },
                                        {
                                            text: idPagnet,
                                            bold: true,
                                            color: '#333333',
                                            fontSize: 12,
                                            alignment: 'right',
                                            width: 120,
                                        },
                                    ],
                                },
                                {
                                    columns: [
                                    {
                                        text: 'Date : ',
                                        color: 'black',
                                        bold: true,
                                        width: '*',
                                        fontSize: 12,
                                        alignment: 'right',
                                    },
                                    {
                                        text: heure_date,
                                        bold: true,
                                        color: '#333333',
                                        fontSize: 12,
                                        alignment: 'right',
                                        width: 120,
                                    },
                                    ],
                                },
                                {
                                    columns: [
                                    {
                                        text: 'En service :',
                                        color: 'black',
                                        bold: true,
                                        fontSize: 12,
                                        alignment: 'right',
                                        width: '*',
                                    },
                                    {
                                        text: lignes[12],
                                        bold: true,
                                        fontSize: 12,
                                        alignment: 'right',
                                        width: 120,
                                    },
                                    ],
                                }, 
                                ],
                            },
                        ],
                        },
                        '\n',
                        {
                        layout: {
                            defaultBorder: false,
                            hLineWidth: function(i, node) {
                            return 1;
                            },
                            vLineWidth: function(i, node) {
                            return 1;
                            },
                            hLineColor: function(i, node) {
                            if (i === 1 || i === 0) {
                                return 'black';
                            }
                            return 'black';
                            },
                            vLineColor: function(i, node) {
                            return 'black';
                            },
                            hLineStyle: function(i, node) {
                            // if (i === 0 || i === node.table.body.length) {
                            return null;
                            //}
                            },
                            // vLineStyle: function (i, node) { return {dash: { length: 10, space: 4 }}; },
                            paddingLeft: function(i, node) {
                            return 10;
                            },
                            paddingRight: function(i, node) {
                            return 10;
                            },
                            paddingTop: function(i, node) {
                            return 2;
                            },
                            paddingBottom: function(i, node) {
                            return 2;
                            },
                            fillColor: function(rowIndex, node, columnIndex) {
                            return '#fff';
                            },
                        },
                        table: {
                            headerRows: 1,
                            widths: ['5%', '65%',  '6%', '12%', '12%'],
                            body: [
                                [
                                    {
                                        text: 'No',
                                        border: [true, true, true, true],
                                        margin: [0, 4, 0, 4],
                                        textTransform: 'uppercase',
                                        alignment: 'center',
                                        bold: true,
                                    },
                                    {
                                        text: 'Description',
                                        border: [true, true, true, true],
                                        margin: [0, 4, 0, 4],
                                        textTransform: 'uppercase',
                                        alignment: 'center',
                                        bold: true,
                                    },
                                    {
                                        text: 'Qte',
                                        border: [true, true, true, true],
                                        alignment: 'center',
                                        margin: [0, 4, 0, 4],
                                        textTransform: 'uppercase',
                                        bold: true,
                                    },
                                    {
                                        text: 'PU',
                                        border: [true, true, true, true],
                                        alignment: 'center',
                                        margin: [0, 4, 0, 4],
                                        textTransform: 'uppercase',
                                        bold: true,
                                    },
                                    {
                                        text: 'Total',
                                        border: [true, true, true, true],
                                        alignment: 'center',
                                        margin: [0, 4, 0, 4],
                                        textTransform: 'uppercase',
                                        bold: true,
                                    },
                                ],

                                ...columns,
                            ],
                        },
                        },
                        '\n',
                        {
                        layout: {
                            defaultBorder: false,
                            hLineWidth: function(i, node) {
                            return 1;
                            },
                            vLineWidth: function(i, node) {
                            return 1;
                            },
                            hLineColor: function(i, node) {
                            return 'black';
                            },
                            vLineColor: function(i, node) {
                            return 'black';
                            },
                            hLineStyle: function(i, node) {
                            // if (i === 0 || i === node.table.body.length) {
                            return null;
                            //}
                            },
                            // vLineStyle: function (i, node) { return {dash: { length: 10, space: 4 }}; },
                            paddingLeft: function(i, node) {
                            return 7;
                            },
                            paddingRight: function(i, node) {
                            return 7;
                            },
                            paddingTop: function(i, node) {
                            return 1;
                            },
                            paddingBottom: function(i, node) {
                            return 1;
                            },
                            fillColor: function(rowIndex, node, columnIndex) {
                            return '#fff';
                            },
                        },
                        table: {
                            headerRows: 1,
                            widths: ['30%', '20%','0%', '30%', '20%'],
                            body: [
                                [
                                    {
                                    text: 'Total Panier',
                                    border: [true, true, true, true],
                                    alignment: 'left',
                                    margin: [0, 4, 0, 4],
                                    bold: true,
                                    },
                                    {
                                    border: [true, true, true, true],
                                    text: montant_total,
                                    alignment: 'right',
                                    fillColor: '#f5f5f5',
                                    margin: [0, 4, 0, 4],
                                    },
                                    {
                                    text: '',
                                    border: [false, false, false, false]
                                    },
                                    {
                                    text: 'Compte',
                                    border: [true, true, true, true],
                                    alignment: 'left',
                                    margin: [0, 4, 0, 4],
                                    bold: true,
                                    },
                                    {
                                    border: [true, true, true, true],
                                    text: type_compte,
                                    alignment: 'right',
                                    fillColor: '#f5f5f5',
                                    margin: [0, 4, 0, 4],
                                    },
                                ],
                                [
                                    {
                                    text: 'Remise',
                                    border: [true, true, true, true],
                                    alignment: 'left',
                                    margin: [0, 4, 0, 4],
                                    bold: true,
                                    },
                                    {
                                    border: [true, true, true, true],
                                    text: montant_remise,
                                    alignment: 'right',
                                    fillColor: '#f5f5f5',
                                    margin: [0, 4, 0, 4],
                                    },
                                    {
                                    text: '',
                                    border: [false, false, false, false]
                                    },
                                    {
                                    text: 'Especes',
                                    border: [true, true, true, true],
                                    alignment: 'left',
                                    margin: [0, 4, 0, 4],
                                    bold: true,
                                    },
                                    {
                                    border: [true, true, true, true],
                                    text: montant_especes,
                                    alignment: 'right',
                                    fillColor: '#f5f5f5',
                                    margin: [0, 4, 0, 4],
                                    },
                                ],
                                [
                                    {
                                    text: 'Total a Payer',
                                    bold: true,
                                    fontSize: 13,
                                    alignment: 'left',
                                    border: [true, true, true, true],
                                    margin: [0, 4, 0, 4],
                                    },
                                    {
                                    text: montant_apayer,
                                    bold: true,
                                    fontSize: 13,
                                    alignment: 'right',
                                    border: [true, true, true, true],
                                    fillColor: '#f5f5f5',
                                    margin: [0, 4, 0, 4],
                                    },
                                    {
                                    text: '',
                                    border: [false, false, false, false]
                                    },
                                    {
                                    text: 'Rendu',
                                    border: [true, true, true, true],
                                    alignment: 'left',
                                    margin: [0, 4, 0, 4],
                                    bold: true,
                                    },
                                    {
                                    border: [true, true, true, true],
                                    text: montant_rendu,
                                    alignment: 'right',
                                    fillColor: '#f5f5f5',
                                    margin: [0, 4, 0, 4],
                                    },
                                ],
                            ],
                        },
                        },
                        '\n',
                        {
                            layout: {
                                defaultBorder: false,
                                hLineWidth: function(i, node) {
                                return 1;
                                },
                                vLineWidth: function(i, node) {
                                return 1;
                                },
                                hLineColor: function(i, node) {
                                if (i === 1 || i === 0) {
                                    return 'black';
                                }
                                return 'black';
                                },
                                vLineColor: function(i, node) {
                                return 'black';
                                },
                                hLineStyle: function(i, node) {
                                // if (i === 0 || i === node.table.body.length) {
                                return null;
                                //}
                                },
                                // vLineStyle: function (i, node) { return {dash: { length: 10, space: 4 }}; },
                                paddingLeft: function(i, node) {
                                return 1;
                                },
                                paddingRight: function(i, node) {
                                return 1;
                                },
                                paddingTop: function(i, node) {
                                return 2;
                                },
                                paddingBottom: function(i, node) {
                                return 2;
                                },
                                fillColor: function(rowIndex, node, columnIndex) {
                                return '#fff';
                                },
                            },
                            table: {
                                headerRows: 1,
                                widths: ['50%',  '50%'],
                                body: [
                                    [
                                        {
                                            qr: infos_Qr,
                                            fit: '50',
                                            alignment: 'center',
                                            //border: [true, true, true, true],
                                            margin: [8, 0, 0, 0],
                                        },
                                        {
                                            image: val_barcode,
                                            width: 250,
                                            height: 42,
                                            alignment: 'right',
                                            //border: [true, true, true, true],
                                            //margin: [4, 0, 0, 0],
                                        }
                                    ],
                                ],
                                
                            },
                        },
                    ],
                    defaultStyle: {
                        columnGap: 20,
                        //font: 'Quicksand',
                    },
                };

              pdfMake.createPdf(docDefinition).download('facture_#'+idPagnet+'.pdf') ; 

            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
        

        // function GetParameterValues(param) {
        //   var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        //   for (var i = 0; i < url.length; i++) {
        //     var urlparam = url[i].split('=');
        //     if (urlparam[0] == param) {
        //       return urlparam[1];
        //     }
        //   }
        // }

      });
      setTimeout("self.close()", 9000 );
    </script>

</html>