<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Currency extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function generate_currencies()
    {
        $this->load->model('Currency_model');
        $json = file_get_contents('https://free.currencyconverterapi.com/api/v6/currencies');
        $currencies = json_decode($json);
        foreach ($currencies->results as $currency) {
            echo json_encode($currency);
            $this->Currency_model->id_currency = $currency->id;
            $this->Currency_model->name = $currency->currencyName;
            if (property_exists($currency, "currencySymbol")) {
                $this->Currency_model->symbol = $currency->currencySymbol;
            } else {
                $this->Currency_model->symbol = null;
            }
            $this->Currency_model->insert();
        }
    }

    public function update_currencies_values()
    {
        set_time_limit(300);
        $this->load->helper('app_helper');
        $timestamp = actual_date();
        $this->load->model('Currency_model');
        $currencies = $this->Currency_model->get_all();
        foreach ($currencies as $currency) {
            $json = file_get_contents('https://free.currencyconverterapi.com/api/v6/convert?q='.$currency->id_currency.'_USD&compact=y');
            $value = json_decode($json, true);
            echo json_encode($value);
            $currency->update_value($value[$currency->id_currency."_USD"]["val"], $timestamp);
        }
    }

    public function convert_value()
    {
        $amount = $this->input->post('input-amount');
        $to = $this->input->post('select-to');
        $from = $this->input->post('select-from');
        $this->load->model('Currency_model');
        echo $this->Currency_model->convert_amount($from, $to, $amount, true);
    }

    public function get_historical_conversion()
    {
        $this->load->model('Currency_model');
        $from = $this->Currency_model->get_single('USD');
        $to = $this->Currency_model->get_single('ARS');
        $results = $from->get_historical_conversion($to);
        echo json_encode($results);
    }

    public function get_historical_conversion_html()
    {
        $to = $this->input->post('select-to');
        $from = $this->input->post('select-from');
        $this->load->model('Currency_model');
        $toCurrency =  $this->Currency_model->get_single($to);
        $fromCurrency =  $this->Currency_model->get_single($from);
        $results = $fromCurrency->get_historical_conversion($toCurrency);
        $data["results"] = $results;
        $data["fromCurrency"] = $fromCurrency;
        $data["toCurrency"] = $toCurrency;
        $html = $this->load->view('modals/chart', $data, true);
        echo $html;
    }
}
