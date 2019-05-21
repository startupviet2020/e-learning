<?
namespace SHLX\MySQL\Dal;
use HV\Core\Db as Db;

class Exams extends Db\Dal{
	public function addEvent($data){
		$entity = DbEvents::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);
	}

	public function getTraineeBySBD($eid, $sbd){
		$entity = DbTrainees::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM trainees WHERE sbd = ? AND eid = ?";
		return $entity->exec_object($conn, $sql, array($sbd, $eid));	
	}

	public function getTrainee($tid){
		$entity = DbTrainees::getInstance();
		$conn = $this->getDb($entity);
		return $entity->loadById($conn, $tid);
	}

	public function getTraineesByLevel($sql){
		$entity = DbTrainees::getInstance();
		$conn = $this->getDb($entity);
		return $entity->exec_query($conn, $sql, array());
	}

	public function updateTrainee($tid, $data){
		$entity = DbTrainees::getInstance();
		$conn = $this->getDb($entity);
		return $entity->updateById($conn, $data, $tid);	
	}

	public function addTrainee($data){
		$entity = DbTrainees::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);	
	}

	public function deleteTrainee($id){
		$entity = DbTrainees::getInstance();
		$conn = $this->getDb($entity);
		return $entity->deleteById($conn, $id);
	}

	public function resetTrainee($id){
		$entity = DbTrainees::getInstance();
		$conn = $this->getDb($entity);
		$sql = "DELETE FROM sessions WHERE tid = ?";
		$entity->exec_no_query($conn, $sql, array($id));

		$sql = "DELETE FROM events WHERE tid = ?";
		$entity->exec_no_query($conn, $sql, array($id));

		$entity->updateById($conn, array("status" => 0, "mark" => 0, "result" => 0), $id);
	}

	public function retryTrainee($id, $seq_id){
		$entity = DbTrainees::getInstance();
		$conn = $this->getDb($entity);
		$entity->updateById($conn, array("seq_id" => $seq_id, "status" => 0, "result" => 0, "mark" => 0, "length" => 0), $id);
	}

	public function getCurrentSession($tid){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM sessions WHERE tid = ? ORDER BY sequence DESC LIMIT 1";
		return $entity->exec_object($conn, $sql, array($tid));
	}

	public function addSession($data){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);
	}

	public function updateSession($sid, $data){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		return $entity->updateById($conn, $data, $sid);	
	}

	public function getSessions($tid){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		return $entity->exec_query($conn, "SELECT * FROM sessions WHERE tid = ?", array($tid));
	}

	public function getSession($sid){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		return $entity->loadById($conn, $sid);
	}

	public function getEvents($sid, $tid){
		$entity = DbEvents::getInstance();
		$sql = "SELECT * FROM events WHERE sid = ? and tid = ? ORDER BY stt";
		$conn = $this->getDb($entity);
		return $entity->exec_query($conn, $sql, array($sid, $tid));
	}

	public function getEvent($sid, $tid, $type, $stt){
		$entity = DbEvents::getInstance();
		$sql = "SELECT * FROM events WHERE sid = ? AND tid = ? and type = ? AND stt = ?";
		$conn = $this->getDb($entity);
		return $entity->exec_object($conn, $sql, array($sid, $tid, $type, $stt));	
	}

	public function getDevice($id){
		$entity = DbDevices::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM devices WHERE id = ?";
		return $entity->exec_object($conn, $sql, array($id));
	}

	public function getDeviceByBox($id){
		$entity = DbDevices::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM devices WHERE box_id = ?";
		return $entity->exec_object($conn, $sql, array($id));
	}

	public function getDevices(){
		$entity = DbDevices::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM devices";
		return $entity->exec_query($conn, $sql, array());
	}

	public function updateDevice($id, $data){
		$entity = DbDevices::getInstance();
		$conn = $this->getDb($entity);
		return $entity->updateById($conn, $data, $id);		
	}

	public function addDevice($data){
		$entity = DbDevices::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);
	}

	public function deleteDevice($id){
		$entity = DbDevices::getInstance();
		$conn = $this->getDb($entity);
		return $entity->deleteById($conn, $id);	
	}

	public function addGps($data){
		$entity = DbGps::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);
	}

	public function getGps($tid, $sid){
		$entity = DbGps::getInstance();
		$conn = $this->getDb($entity);
		return $entity->exec_query($conn, "SELECT * FROM gps WHERE tid = ? AND sid = ?", array($tid, $sid));
	}

	public function addImage($data){
		$entity = DbImages::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);	
	}

	public function updateImage($id, $data){
		$entity = DbImages::getInstance();
		$conn = $this->getDb($entity);
		$entity->updateById($conn, $data, $id);	
	}
	
	public function getImages($tid, $sid){
		$entity = DbImages::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM images WHERE tid = ? AND sid = ?";
		return $entity->exec_query($conn, $sql, array($tid, $sid));
	}

	public function getImagesByDevAndTime($dev_id, $started, $ended){
		$entity = DbImages::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM images WHERE dev_id = ? AND created >= ? AND created <= ? AND tid = 0";
		return $entity->exec_query($conn, $sql, array($dev_id, $started, $ended));	
	}

	public function getExams(){
		$entity = DbExams::getInstance();
		$conn = $this->getDb($entity);
		return $entity->exec_query($conn, "SELECT * FROM exams ORDER BY id DESC", array());
	}

	public function getExam($id){
		$entity = DbExams::getInstance();
		$conn = $this->getDb($entity);
		return $entity->loadById($conn, $id);
	}

	public function addExam($data){
		$entity = DbExams::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);	
	}

	public function updateExam($id, $data){
		$entity = DbExams::getInstance();
		$conn = $this->getDb($entity);
		return $entity->updateById($conn, $data, $id);	
	}

	public function deleteExam($id){
		$entity = DbExams::getInstance();
		$conn = $this->getDb($entity);
		return $entity->deleteById($conn, $id);
	}

	public function resetExamStatus(){
		$entity = DbExams::getInstance();
		$conn = $this->getDb($entity);
		$entity->exec_no_query($conn, "UPDATE exams SET status = 0", array());
	}

	public function getActiveExam(){
		$entity = DbDevices::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM exams WHERE status = 1";
		return $entity->exec_object($conn, $sql, array());
	}

	public function addFreeEvent($data){
		$entity = DbFreeEvents::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);	
	}

	public function addFreeGps($data){
		$entity = DbFreeGps::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);			
	}
	
	public function addLogsEvent($data){
		$entity = DbLogsEvents::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);	
	}
	
	public function getImageSession($device_id, $ts){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM sessions WHERE dev_id = ? AND started_time < ? AND (started_time + duration) > ?";
		return $entity->exec_object($conn, $sql, array($device_id, $ts, $ts));
	}
	
	public function findSession($eid, $mark){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT s.* FROM sessions s INNER JOIN trainees t ON s.tid = t.id WHERE s.mark = ? AND t.eid = ? ORDER BY RAND() LIMIT 1";
		return $entity->exec_object($conn, $sql, array($mark, $eid));
	}
	
	public function deleteEvents($sid){
		$entity = DbEvents::getInstance();
		$conn = $this->getDb($entity);
		$sql = "DELETE FROM events WHERE sid = ?";
		$entity->exec_no_query($conn, $sql, array($sid));
	}
	
	public function changeEventsTrainee($sid, $tid){
		$entity = DbEvents::getInstance();
		$conn = $this->getDb($entity);
		$sql = "UPDATE events SET tid=? WHERE sid = ?";
		$entity->exec_no_query($conn, $sql, array($tid, $sid));
	}
	
	public function changeImagesTrainee($sid, $tid){
		$entity = DbImages::getInstance();
		$conn = $this->getDb($entity);
		$sql = "UPDATE images SET tid=? WHERE sid = ?";
		$entity->exec_no_query($conn, $sql, array($tid, $sid));
	}
	
	public function getImagesByDevice($dev_id, $page){
		$entity = DbImages::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM images";
		if ($dev_id > 0){
			$sql = $sql . " WHERE dev_id = " . $dev_id;
		}
		$from = 60*$page;
		$sql = $sql . " ORDER BY created DESC LIMIT " . $from . ",60";
		return $entity->exec_query($conn, $sql, array());
	}
	
	public function getLastDeviceImage($dev_id){
		$entity = DbImages::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT * FROM images WHERE dev_id = ? ORDER BY id DESC LIMIT 1";
		return $entity->exec_object($conn, $sql, array($dev_id));
	}
	
	public function enqueue($data){
		$entity = DbQueue::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);	
	}
	
	public function dequeue($id){
		$entity = DbQueue::getInstance();
		$conn = $this->getDb($entity);
		return $entity->loadById($conn, $id);
	}
	
	public function deleteQueueItem($id){
		$entity = DbQueue::getInstance();
		$conn = $this->getDb($entity);
		return $entity->deleteById($conn, $id);
	}
	
	public function getQueueItems(){
		$entity = DbQueue::getInstance();
		$conn = $this->getDb($entity);
		$sql = "SELECT id FROM queue ORDER BY id LIMIT 1";
		return $entity->exec_query($conn, $sql, array());
	}
	
	public function scanImages($tid, $sid, $started_time, $duration, $dev_id){
		$entity = DbImages::getInstance();
		$conn = $this->getDb($entity);
		
		$sql = "SELECT * FROM images WHERE created >= ? AND created <= ? AND dev_id = ?";
		$images = $entity->exec_query($conn, $sql, array($started_time, $started_time + $duration, $dev_id));
		
		$sql = "UPDATE images SET tid = ?, sid = ? WHERE created >= ? AND created <= ? AND dev_id = ?";
		$entity->exec_no_query($conn, $sql, array($tid, $sid, $started_time, $started_time + $duration, $dev_id));
		return $images;
	}
}