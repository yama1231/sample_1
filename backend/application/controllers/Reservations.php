<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservations extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('reservation_model');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        
        if ($this->input->server('REQUEST_METHOD') == 'OPTIONS') {
            return;
        }
    }

    public function index() {
        if ($this->input->server('REQUEST_METHOD') !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $reservations = $this->reservation_model->get_all_reservations();
        echo json_encode(['data' => $reservations]);
    }

    public function show($id) {
        if ($this->input->server('REQUEST_METHOD') !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $reservation = $this->reservation_model->get_reservation($id);
        
        if ($reservation) {
            echo json_encode(['data' => $reservation]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Reservation not found']);
        }
    }

    public function create() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }

        $required_fields = ['customer_name', 'customer_email', 'service_type', 'reservation_date', 'reservation_time'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Field {$field} is required"]);
                return;
            }
        }

        $data = array(
            'customer_name' => $input['customer_name'],
            'customer_email' => $input['customer_email'],
            'customer_phone' => isset($input['customer_phone']) ? $input['customer_phone'] : null,
            'service_type' => $input['service_type'],
            'reservation_date' => $input['reservation_date'],
            'reservation_time' => $input['reservation_time'],
            'duration' => isset($input['duration']) ? $input['duration'] : 60,
            'notes' => isset($input['notes']) ? $input['notes'] : null
        );

        if ($this->reservation_model->create_reservation($data)) {
            http_response_code(201);
            echo json_encode(['message' => 'Reservation created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create reservation']);
        }
    }

    public function update($id) {
        if ($this->input->server('REQUEST_METHOD') !== 'PUT') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }

        $existing_reservation = $this->reservation_model->get_reservation($id);
        if (!$existing_reservation) {
            http_response_code(404);
            echo json_encode(['error' => 'Reservation not found']);
            return;
        }

        $allowed_fields = ['customer_name', 'customer_email', 'customer_phone', 'service_type', 
                          'reservation_date', 'reservation_time', 'duration', 'status', 'notes'];
        
        $data = array();
        foreach ($allowed_fields as $field) {
            if (isset($input[$field])) {
                $data[$field] = $input[$field];
            }
        }

        if ($this->reservation_model->update_reservation($id, $data)) {
            echo json_encode(['message' => 'Reservation updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update reservation']);
        }
    }

    public function delete($id) {
        if ($this->input->server('REQUEST_METHOD') !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $existing_reservation = $this->reservation_model->get_reservation($id);
        if (!$existing_reservation) {
            http_response_code(404);
            echo json_encode(['error' => 'Reservation not found']);
            return;
        }

        if ($this->reservation_model->delete_reservation($id)) {
            echo json_encode(['message' => 'Reservation deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete reservation']);
        }
    }

    public function services() {
        if ($this->input->server('REQUEST_METHOD') !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $services = $this->reservation_model->get_services();
        echo json_encode(['data' => $services]);
    }
}