<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{

    public function index()
    {
        // Здесь будем отображать главную страницу
        $this->load->view('templates/header');
        $this->load->view('templates/home');
        $this->load->view('templates/footer');
    }

    public function restoran()
    {
        // Здесь будем отображать страницу "О нас"
        $this->load->view('templates/header');
        $this->load->view('templates/about');
        $this->load->view('templates/footer');
    }

    public function reserve_table()
    {
        // Обработка формы заказа столика
        $this->load->library('form_validation');
        $this->load->library('input');
        $this->load->database();

        $this->form_validation->set_rules('name', 'Ваше имя', 'required');
        $this->form_validation->set_rules('phone', 'Ваш телефон', 'required');
        $this->form_validation->set_rules('date', 'Дата', 'required');
        $this->form_validation->set_rules('time', 'Время', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Если валидация не прошла, вернуть обратно к форме с ошибками
            $this->load->view('templates/header');
            $this->load->view('templates/home');
            $this->load->view('templates/footer');
        } else {
            // Валидация прошла успешно, сохраняем данные в базу данных
            $data = array(
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'date' => $this->input->post('date'),
                'time' => $this->input->post('time'),
                'message' => $this->input->post('message')
            );

            $this->db->insert('reservations', $data);

            // Ваша логика обработки формы
            // Здесь можно добавить запись в базу данных или отправить уведомление
            // В данном примере просто вернем сообщение об успешном бронировании
            echo "Столик успешно забронирован!";
        }
    }
}
