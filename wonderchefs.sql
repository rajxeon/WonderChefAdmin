-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2017 at 03:37 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wonderchefs`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GenerateNode` (`c_parent_id` INT)  BEGIN
declare c_node_id int;
declare c_node_text varchar(345);
declare flag int default 0;
declare treeview cursor for select id,`name` from jobpost where parent=c_parent_id;
declare continue handler for not found set flag=1;
open treeview;

tree_loop : loop
	fetch treeview into c_node_id,c_node_text;
    if(flag=1) then
    leave tree_loop;
    end if;
		begin
		declare counter int;
		select count(*) into counter from jobpost where parent=c_node_id; 
		if(counter>0 and c_node_id>0) then
			insert into TreeView value(concat('<li><span class="parents">',c_node_text,'</span><ol>'));
			call GenerateNode(c_node_id);
			insert into TreeView value('</ol></li>');
		else
			insert into TreeView value(concat('<li>',c_node_text,'</li>'));
		end if;
	end;
end loop tree_loop;
 
close treeview;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GenerateTreeView` ()  BEGIN
set session max_sp_recursion_depth=20;
create temporary table TreeView(
tree_rows varchar(345)
);

insert into TreeView value('<ol>');
call GenerateNode(0);
insert into TreeView value('</ol>');
select group_concat(tree_rows separator '') as g from TreeView;

drop table TreeView;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `token` varchar(128) NOT NULL,
  `lastLoggedin` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`, `token`, `lastLoggedin`) VALUES
(1, 'Raj', 'rajxeon', '61E2484AE1AFA50C36068429D1EB1EEA', '3c892ba73d0ce395f29936f5a5c965882066ebee', '2017-08-30 09:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `chefs`
--

CREATE TABLE `chefs` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `address` varchar(50) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '3',
  `experience` int(11) NOT NULL DEFAULT '0',
  `addedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chefs`
--

INSERT INTO `chefs` (`id`, `name`, `phone`, `active`, `address`, `rating`, `experience`, `addedOn`) VALUES
(1, 'Test 1', '9475956718', 1, 'Address', 3, 2, '2017-08-30 07:17:26'),
(2, 'Test 2', '9475956718', 1, 'Address', 3, 2, '2017-08-30 07:17:26'),
(7, 'asd', '321', 1, '234', 3, 324, '2017-08-30 07:41:13'),
(6, '123', '23', 0, '234', 3, 324, '2017-08-30 07:40:48'),
(8, 'asd33', '321', 1, '234', 3, 324, '2017-08-30 07:41:24'),
(11, 'asd4', '32', 0, '234', 3, 23465, '2017-08-30 09:40:19');

-- --------------------------------------------------------

--
-- Table structure for table `jobpost`
--

CREATE TABLE `jobpost` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobpost`
--

INSERT INTO `jobpost` (`id`, `name`, `parent`) VALUES
(1, 'Root', 0),
(2, 'Child_1_1', 4),
(3, 'Child_1_2', 4),
(4, 'Root_1', 0),
(5, 'Root_2', 0),
(6, 'Child_2_2', 5),
(7, 'Child_2_1', 5),
(8, 'Child_2_1_1', 7),
(9, 'Child_2_1_2', 7),
(10, 'Child_2_1_3', 7),
(11, 'Child_2_1_3_1', 8),
(13, 'Test', 1),
(14, 'Test2', 1),
(15, 'Test', 14);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chefs`
--
ALTER TABLE `chefs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobpost`
--
ALTER TABLE `jobpost`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `chefs`
--
ALTER TABLE `chefs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `jobpost`
--
ALTER TABLE `jobpost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
