<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
  <?php $this->load->view('commons/head_data'); ?>
</head>
<body>
    <div class="outer">
      <div class="middle">
        <div class="inner">
          <h1>De Jesus & Carzo's Conversor</h1>
          <p>Ingeniería de software 2018</p>
          <div class="content-div">
            <form>
              <div class="column">

                <div class="content-column">
                  <input type="number" class="black-text c-content" id="input-amount" name="input-amount"></input>
                </div>

                <div class="content-column">
                  <select class="black-text c-content" id="select-from" name="select-from">
                    <?php
                    foreach ($currencies as $currency) {
                        ?>
                      <option value="<?php echo $currency->id_currency; ?>" <?php if ($currency->id_currency == 'USD') {
                            echo 'selected="selected"';
                        } ?>>
                        <?php echo($currency->name ." - ".$currency->id_currency); ?>
                      </option>
                    <?php
                    }
                    ?>
                  </select>
                </div>

              </div>

            <div class="column">

              <div class="content-column">
                <button class="c-content black-text" disabled>convertir a</button>
              </div>

              <div class="content-column">
                <select class="black-text c-content" id="select-to" name="select-to">
                  <?php
                  foreach ($currencies as $currency) {
                      ?>
                    <option value="<?php echo $currency->id_currency; ?>" <?php if ($currency->id_currency == 'ARS') {
                          echo 'selected="selected"';
                      } ?>>
                      <?php echo($currency->name ." - ".$currency->id_currency); ?>
                    </option>
                  <?php
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="full-column">
              <div class="column halfeable">
                <span id="result-label" style="display:none; padding-left:25%;">Resultado:</span>
              </div>
              <div class="column halfeable">
                <img src="assets/img/loading.gif" style="display: none; width:15px; height: 15px; margin-right:25%;" id="loading-img"> <span id="result-number" style="display:none; padding-right:25%;"></span>
              </div>
            </div>

            <div class="full-column">
              <button type="button" class="btn btn-default btn-sm" onclick="convert()">
                <span class="glyphicon glyphicon-refresh"></span> Convertir
              </button>

              <button type="button" class="btn btn-default btn-sm"  onclick="getHistorical()">
                <span class="glyphicon glyphicon-time"></span> Historico
              </button>
            </div>

          </form>



          <div class="full-column" style="margin-top: 40px;">
            <span class="full-row-text">  Últimas cotizaciones: </span>
            <marquee><?php echo $marquee ?></marquee>
          </div>

        </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="myModal" aria-hidden="true" style="background-color:white; height:80%;width:95%;margin:auto;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Historial de cambios</h3>
        </div>
        <div class="modal-body" id="modal-body">
        </div>
        <div class="modal-footer">
            <a href="javascript:;" class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>


</body>
</html>

<script>

function getHistorical()
{
  if (areDistinctCurrencies())
  {
    $('#myModal').modal('show');
    $("#modal-body").html(" ");
    var img = $('<img id="modal-loading">');
    img.appendTo('#modal-body');
    var data = $("form").serialize();
    $.post("http://cconverter.netdevweb.com/index.php/Currency/get_historical_conversion_html", data,
    function(data)
    {
      debugger;
      $("#modal-body").html(data);
    });
  }
}

function convert()
{
  if (validate())
  {
    $("#result-number").hide();
    $("#loading-img").show();
    $("#result-label").slideDown(200);
    var data = $("form").serialize();
    $.post("http://cconverter.netdevweb.com/index.php/Currency/convert_value", data,
  function(data)
  {
      $("#loading-img").hide();
      $("#result-number").html(data);
      $("#result-number").fadeIn(1000);
  });
  }

}


function validate()
{
  return (isNumberSetted() & areDistinctCurrencies());
}

function isNumberSetted()
{
  if ($("#input-amount").val() == "")
  {
      $.notify("Ingrese un valor numérico a convertir",
      {
        animate: {
          enter: 'animated fadeInRight',
          exit: 'animated fadeOutRight'
        },
        type: 'danger'
      });
      return false;
  }
  return true;
}

function areDistinctCurrencies()
{
  if ($("#select-to").val() == $("#select-from").val())
  {
      $.notify("Ingrese 2 monedas distintas",
      {
        animate: {
          enter: 'animated fadeInRight',
          exit: 'animated fadeOutRight'
        },
        type: 'danger'
      });
      return false;
  }
  return true;
}
</script>
