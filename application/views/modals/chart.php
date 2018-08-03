
      <div id="container"></div>
      <script>
          var data = JSON.parse('<?php echo json_encode($results) ?>');
          var chartTitle = "<?php echo ($fromCurrency->id_currency.' a '.$toCurrency->id_currency) ?>";
          var parsedData = new Array();
          for (var i=0; i<data.length; i++)
          {
            var val = data[i];
            parsedData.push([(new Date(val.timestamp)).getTime(),parseFloat(parseFloat(val.value).toFixed(2))])
          }
          $(function () {
            let myChart = Highcharts.stockChart('container', {
                    rangeSelector: {
                      selected: 1
                    },
                    series: [{
                        name: chartTitle,
                        data: parsedData
                    }]
                });
            });

      </script>
