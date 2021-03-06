<?php
/*
@author  nikifalex
@skype   logoffice1
@link    http://opencartmodules.ru
*/


class ModelExtensionTotalDiscountsTotal extends Model {

    public function getTotal($total) {
        if (!$this->config->get('discounts_total_status')) {
            return;
        }

        $discounts_table=$this->config->get('discounts_total_discounts_table');
        //print_r($discounts_table);
        if (!is_array($discounts_table)) {
            return;
        }

        $last_days=$this->config->get('discounts_total_last_days');
        if ($last_days=='')
            $last_days=0;

        if ($this->customer->isLogged()) {
            $this->language->load('extension/total/discounts_total');

            if (isset($this->session->data['order_id']))
                $order_id = $this->session->data['order_id'];
            else
                $order_id = '';
            $total_orders = $this->getOrdersTotal($order_id,$last_days);
            if ($total_orders == '')
                $total_orders = 0;


            usort($discounts_table, array('ModelExtensionTotalDiscountsTotal', 'cmpd'));



            $discount=0;
            foreach ($discounts_table as $d) {
                if ($total_orders>=$d['summ'])
                    $discount=$d['value'];
            }

            $discounts_total_calc_type_id=$this->config->get('discounts_total_calc_type_id');
            if ($discounts_total_calc_type_id==0) {
                $discount_total=$total['total']*($discount/100);
            } elseif (in_array($discounts_total_calc_type_id,array(1,2))) {
                $products=$this->cart->getProducts();
                $discount_total=0;
                $this->load->model('catalog/product');
                foreach ($products as $product) {
                    if ($discounts_total_calc_type_id==1) {
                        $productb=$this->model_catalog_product->getProduct($product['product_id']);
                        $productd=$this->model_catalog_product->getProductDiscounts($product['product_id']);
                        if (!$productb['special'] && count($productd)==0) {
                            $discount_total += $product['total'] * ($discount / 100);
                        }
                    }
                    if ($discounts_total_calc_type_id==2) {
                        $discount_total += $product['total'] * ($discount / 100);
                    }
                }
            } else {
                $discount_total=0;
            }




            if ($discount_total > 0) {
                $total['totals'][] = array(
                    'code' => 'discounts_total',
                    'title' => $this->language->get('text_title').' '.$discount.'%',
                    'value' => -$discount_total,
                    'sort_order' => $this->config->get('discounts_total_sort_order')
                );
                $total['total'] -= $discount_total;
            }
        }
    }

    public function getOrdersTotal($order_id='',$last_days) {

        $status=$this->config->get('discounts_total_order_status_id');
        if ((int)$last_days>0) {
            $sql = "SELECT sum(o.total) total FROM `" . DB_PREFIX . "order` o  WHERE o.date_added >= NOW() - INTERVAL " . (int)$last_days . " DAY AND o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id = '" . (int)$status . "' ";
        }
        else {
            $sql = "SELECT sum(o.total) total FROM `" . DB_PREFIX . "order` o  WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id = '" . (int)$status . "' ";
        }

        if ($order_id!='')
            $sql.=" AND o.order_id<>'".$order_id."'";

        $query = $this->db->query($sql);
        //print_r($query);
        return $query->row['total'];
    }

    public function getOrdersTotals($order_id='',$last_days) {

        $status=$this->config->get('discounts_total_order_status_id');
        if ((int)$last_days>0) {
            $sql = "SELECT sum(o.total) total FROM `" . DB_PREFIX . "order` o  WHERE o.date_added >= NOW() - INTERVAL " . (int)$last_days . " DAY AND o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id = '" . (int)$status . "' ";
        }
        else {
            $sql = "SELECT sum(o.total) total FROM `" . DB_PREFIX . "order` o  WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id = '" . (int)$status . "' ";
        }

        if ($order_id!='')
            $sql.=" AND o.order_id<>'".$order_id."'";

        $query = $this->db->query($sql);




        if($query->row['total'])
        {
            $summ=$query->row['total'];
        }
        else
        {
            $summ=0;

        }

        $sale=0;
        $sql = "SELECT o.total, o.order_id, o.date_added FROM `" . DB_PREFIX . "order` o  WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id = '" . (int)$status . "' ";
        $query = $this->db->query($sql);
        //print_r($query->rows);
        foreach($this->config->get('discounts_total_discounts_table') as $tot)
        {
            if($tot['summ']<=$summ)
            {
                $sale=$tot['value'];
            }

        }

        $data=array(
            'table'=>$this->config->get('discounts_total_discounts_table'),
            'summ'=>$summ,
            'orders'=>$query->rows,
            'sale'=>$sale
        );

        //print_r($query);
        return $data;
    }

    private static function cmpd($a, $b)
    {
        if ($a["summ"]==$b["summ"]) {
            return 0;
        }
        return ($a["summ"]<$b["summ"]) ? -1 : 1;
    }


}

?>