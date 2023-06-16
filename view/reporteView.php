<title>Tableros Power Bi | Axity</title>
<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.ico">
<script src="lib/jquery/jquery-3.4.1.min.js"></script>
<script src="assets/js/powerbi.js"></script>
<div id="reportContainer" allowfullscreen=""></div>

<script>
    // Get models. models contains enums that can be used.
    var models = window['powerbi-client'].models;

    // Embed configuration used to describe the what and how to embed.
    // This object is used when calling powerbi.embed.
    // This also includes settings and options such as filters.
    // You can find more information at https://github.com/Microsoft/PowerBI-JavaScript/wiki/Embed-Configuration-Details.

    var embedConfiguration = {
        type: 'report',
        id: '<?php echo $id_reporte; ?>', // the report ID
        embedUrl: "https://app.powerbi.com/reportEmbed?reportId=<?php echo $id_reporte; ?>&groupId=<?php echo $group; ?>",
        accessToken: "<?php echo $access_token; ?>"
    };

    var $reportContainer = $('#reportContainer');
    var report = powerbi.embed($reportContainer.get(0), embedConfiguration);

</script>