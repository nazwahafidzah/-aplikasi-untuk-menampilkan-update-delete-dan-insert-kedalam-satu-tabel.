-- Create the database
CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;

-- Create the students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    nim VARCHAR(20) NOT NULL,
    jurusan VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some sample data
INSERT INTO students (name, nim, jurusan, alamat) VALUES
('Budi Santoso', '2021001', 'Teknik Informatika', 'Jl. Merdeka No. 123, Jakarta Selatan'),
('Dewi Lestari', '2021002', 'Sistem Informasi', 'Jl. Diponegoro No. 45, Bandung'),
('Ahmad Hidayat', '2021003', 'Ilmu Komputer', 'Jl. Pahlawan No. 67, Surabaya');
