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
	header('Location:../index.php');
}
require('connection.php');

require('connectionPDO.php');

require('declarationVariables.php');

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <script type="text/javascript" src="js/pdfmake.min.js"></script>
        <script type="text/javascript" src="js/vfs_fonts.js"></script>
        <script src="js/jquery-3.1.1.min.js"></script>
    </head>
    <body>
        <span style="display:none" id="spn_Image"></span> 
        <span style="display:none" id="spn_Barcode"></span> 
    </body>    
    <script>
      $(document).ready(function () {
        var idBon = GetParameterValues('id');

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
        var url_image = './logo/'+image_Boutique;

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
        url_barcode='barcode128.php?text='+idBon+'&codetype='+type+'&orientation='+orientation+'&size='+size+'&print='+print;
        toDataURL(url_barcode, function(dataURL){ $('#spn_Barcode').text(dataURL);})

        $.ajax({
            url:"calculs/detailsFournisseur.php",
            method:"POST",
            data:{
                operation:'detailsBon',
                idBon : idBon,
            },
            success: function (data) {
              val_image=$('#spn_Image').text();
              val_barcode=$('#spn_Barcode').text();

              var lignes = JSON.parse(data);
              fournisseur = lignes[1];
              if(fournisseur!=0){
                var nom=lignes[1].split('<>')[0];
                var adresse=lignes[1].split('<>')[1];
                var telephone=lignes[1].split('<>')[2];
                var nom_fournisseur=nom+' \n  '+adresse+' \n  '+telephone;
              }
              else { var nom_fournisseur=' \n   \n  '; }

              var heure_date=lignes[3]+' '+lignes[2];
              var montant_total = lignes[4]+' F CFA';
              var montant_remise = ' F CFA';
              var montant_apayer = ' F CFA';
              var type_compte = 'Compte';
              var montant_especes = ' F CFA';
              var montant_rendu = ' F CFA';

              var type_facture='Bon Commande';
        
              data = [];
              function setColumns(data) {
                  let tableData = [];

                  var taille = lignes[5].length;
                  var n=1;
                  for(var i = 0; i<taille; i++){
                          tableData.push([
                              {text: n, border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[5][i][1].toLowerCase()+" ("+lignes[5][i][3]+")" , border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'left', fontSize: 10},
                              {text: lignes[5][i][2], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[5][i][4], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
                              {text: lignes[5][i][5], border: [true, true, true, true], margin: [0, 4, 0, 4], alignment: 'center', fontSize: 10},
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
                                    text: 'Fournisseur',
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
                            text: nom_fournisseur,
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
                                            text: idBon,
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
                                        text: lignes[6],
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
                                        text: 'PA',
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
                            widths: ['65%', '35%'],
                            body: [
                                [
                                    {
                                    text: 'Total Bon Commande',
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

              pdfMake.createPdf(docDefinition).download('commande_'+idBon+'.pdf') ; 
              //pdfMake.createPdf(docDefinition).open(); ; 

            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 

        function GetParameterValues(param) {
          var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
          for (var i = 0; i < url.length; i++) {
          var urlparam = url[i].split('=');
            if (urlparam[0] == param) {
              return urlparam[1];
            }
          }
        }
      });
      setTimeout("self.close()", 7500 );
    </script>

</html>