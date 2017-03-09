<?php
	class CronjobsController extends AppController {
		var $uses = array('Item', 'NoteStatus');
		var $components = array('Email');
		var $helpers = array('Html', 'Cropimage', 'Resizeimage');

		function beforeFilter() {
			if (!defined('CRON_DISPATCHER')) {
				$this->redirect('/');
			}

			$this->layout = null;
		}

		function weeklyNotesReport() {
			$ordersStatus = $this->NoteStatus->find('first', array('conditions' => array('NoteStatus.short' => 'new_order')));
			$this->Item->Behaviors->attach('Containable');
			$this->Item->bindModel(
				array(
					'hasMany' => array(
						'Orders' => array(
							'className' => 'Note',
							'foreignKey' => 'item',
							'conditions' => array(
								'TO_DAYS(NOW()) - TO_DAYS(Orders.created) <= 107',
								'Orders.status' => $ordersStatus['NoteStatus']['int'],
								'(Orders.parent IS NULL OR Orders.parent = 0)'
							)
						),
						'AllNotes' => array(
							'className' => 'Note',
							'foreignKey' => 'item',
							'conditions' => array(
								'TO_DAYS(NOW()) - TO_DAYS(AllNotes.created) <= 107',
								'AllNotes.status <>' => $ordersStatus['NoteStatus']['int'],
								'(AllNotes.parent IS NULL OR AllNotes.parent = 0)'
							)
						)
					)
				)
			);

			$report = $this->Item->find('all', array(
					'contain' => array(
						'Orders' => array(
							'Comments'
						),
						'AllNotes' => array(
							'Comments'
						),
						'ItemImage'
					)
				)
			);

			$itemsWithOrders = array();
			$itemsWithNotes = array();
			foreach ($report as $item) {
				if (!empty($item['Orders'])) {
					array_push($itemsWithOrders, $item);
				}
				if (!empty($item['AllNotes'])) {
					array_push($itemsWithNotes, $item);
				}
			}

			$this->Email->to = 'eugeny.bondarenko@office.e2e4gu.ru';
			$this->Email->replyTo = 'Lucca Antiques<info@luccaantiques.com>';
			$this->Email->from = 'Lucca Antiques<info@luccaantiques.com>';
			$this->Email->subject = 'Lucca Studio Updates';
			$this->Email->template = 'notes_report';
			$this->Email->sendAs = 'html';

			$this->set('itemsWithOrders', $itemsWithOrders);
			$this->set('itemsWithNotes', $itemsWithNotes);

			$this->Email->send();

			exit(0);
		}
	}
?>