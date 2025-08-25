<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservation_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_all_reservations() {
        $query = $this->db->get('reservations');
        return $query->result_array();
    }

    public function get_reservation($id) {
        $query = $this->db->get_where('reservations', array('id' => $id));
        return $query->row_array();
    }

    public function create_reservation($data) {
        return $this->db->insert('reservations', $data);
    }

    public function update_reservation($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('reservations', $data);
    }

    public function delete_reservation($id) {
        $this->db->where('id', $id);
        return $this->db->delete('reservations');
    }

    public function get_reservations_by_date($date) {
        $this->db->where('reservation_date', $date);
        $query = $this->db->get('reservations');
        return $query->result_array();
    }

    public function get_services() {
        $this->db->where('is_active', TRUE);
        $query = $this->db->get('services');
        return $query->result_array();
    }
}