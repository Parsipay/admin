<?php
class biiq_User{
    public 
        $ID = null, $Status = 0, $Banned = 0,
        $Profile_Pic = SITE.'assets/profiles/avatar-1.png',
        $Mobile,
        $FirstName = '',
        $LastName = '',
        $Fullname = '', 
        $Birthday,
        $Birthday_Day,
        $Birthday_Month,
        $Birthday_Year,
        $Tell = null, 
        $Email = null, $EmailNotification = 0, $EmailVerified = 0,
        $Meli = null,
        $Credit = 0,
        $GiftedCredit = 0,
        $Type = 'Guest', 
        $Group_ID = 0,
        $TypeHTML = 'مهمان',
        $is_registered = false,
        $RefCount = 0,
        $Creation_DateTime,
        $Creation_DateFA = '',
        $Creation_Date,
        $LastActivity,
        $LastActivity_DateTime = '',
        $AdminNote = '',
        $Authentication = null, $UnfinishedOrders = 0, $SearchQuery = '', $Cryptos = '', $PinnedCryptos = '', $VerifiedStep1 = '', $VerifiedStep2 = '', $VerifiedStep3 = '', $Cryptos_Count = 0, $ActiveBankAccountsCount = 0, $MinAmountToman = 0, $CreditCounts = 0,
        $StatusHTML, $ActiveBankAccounts = [], $TransactionsCounts = 0, $RegisteredDays = 0,
        
        $NotificationCount = 0,
        $Notifications = [], $Objects, 
        $IPAddress,
        $City,
        $Country,
        $Browser,
        $System,
        $SelfiesHTML = '<button class="btn btn-sm btn-warning" type="button" data-toggle="showPane" data-tab="#v-pills-tab" data-pane="#v-pills-documents">تایید نشده یا ارسال نشده</button>',
        $BankAccounts,
        $BankAccounts_Count = 0,
        $HasSelfie = 0,
        $File = 'NULL', $SelfiVerified = false,
        $SMS_Code = null, $Email_Code = null, $Tell_Code = null, $Ref_Code = null,
        
        $Documents,
        $Documents_Count = 0,
        $Transactions,
        $Transaction_Count = 0,
        
        $MobileVerified = 0,
        $TellVerified = 0,
        
        $MaxBuyAmount = 0,
        $IsOnline = false,
        $TodayPurchaseAmount = 0, $TodaySalesAmount = 0,
        $PurchasedAmount = 0, $LastActivity_Date = 0,
        $SaleAmount = 0, 
        $Financial_Level_ID, $Is2FA_Enabled = false,
        $LastNotification = 0, $Index = 0, $Refers_ID = 0,
        $Financial_Level;

    protected 
        $TableName = 'users';
    function __construct(){
        //
    }

    public function fill($row = []){
        if($row != null && is_array($row) && count($row) > 0){
            $this->ID       = $row['id'];
            $this->Index    = $row['id'];
            $f = array(
                'Index'         => 'Index',
                'name'          => 'Fullname',
                'banned'        => 'Banned',
                //'email'     => 'Mail',
                'email_notification' => 'EmailNotification',
                'email'         =>'Email',
                'file'          => 'File',
                'mobile'        => 'Mobile',
                'gifted_credit' => 'GiftedCredit',
                'credit'        => 'Credit',
                'phone'         => 'Tell',
                'user_groupid'  => 'Group_ID',
                'fid'           => 'Financial_Level_ID',
                'ipaddress'     => 'IPAddress',
                'nid'           => 'Meli',
                'birthday'      => 'Birthday',
                'status'        => 'Status',
                'user_selected_file', 'MainFile',
                'sms_code'      => 'SMS_Code',
                'email_code'    => 'Email_Code',
                'tell_code'     => 'Tell_Code',
                'ref_code'      => 'Ref_Code',
                'ref_id'        => 'Refers_ID',
                'admin_note'    => 'AdminNote',
                'last_notification' => 'LastNotification'
            );
            foreach($f as $key => $value){
                if(isset($row[$key])){
                    $this->{$value} = $row[$key];
                }
            }
            if($this->Email != null && strlen($this->Email) > 0){
                $this->Email = strtolower($this->Email);
            }else{
                $this->Email = null;
            }

            

            if(isset($row['last_activity'])){
                //$persian_date_class = new biiq_PersianDate();
                $date = gmdate("Y-m-d\TH:i:s\Z", $row['last_activity']);
                $this->LastActivity = biiq_PersianDate::ToPersianHumanDateTime($date);
                $this->LastActivity_DateTime = ConvertToPersianNumber(biiq_PersianDate::ToPersianDate($row['last_activity']));
                $this->LastActivity_Date = $row['last_activity'];
                $current_time = time();
                $online_time = $current_time - 600;//10 minutes/
                if($row['last_activity'] > $online_time){
                    $this->IsOnline = true;
                }
            }
            if(isset($row['start_time'])){
                //$persian_date_class = new biiq_PersianDate();
                $this->Creation_Date = $row['start_time'];
                $date = gmdate("Y-m-d\TH:i:s\Z", $row['start_time']);
                $this->Creation_DateTime = biiq_PersianDate::ToPersianHumanDateTime($date);
                $this->Creation_DateFA = ConvertToPersianNumber(biiq_PersianDate::ToPersianDate($row['start_time']));
                
            }//UNIX_TIMESTAMP
            if(isset($row['status'])){
                //$this->GetStatus($row['status']);
            }
            
            if($this->Birthday != null){
                $temp_birthday      = $this->Birthday;
                $temp_birthday      = explode("/", $temp_birthday);
                if(is_array($temp_birthday) && count($temp_birthday) == 3){
                    $this->Birthday_Day     = right("0".$temp_birthday[2], 2);
                    $this->Birthday_Month   = $temp_birthday[1];
                    $this->Birthday_Year    = $temp_birthday[0];
                }
            }
            return $this;
        }
    }

    public static function GetAll($filter = '1'){
        $return_items = array();
        $get = $GLOBALS['get_req'];
        $current_page = 1;
        $limit = 20;
        if(isset($get['page']) && is_numeric($get['page']) && $get['page'] > 1){
            $current_page = $get['page'];
        }
        if(isset($get['limit'])){
            switch($get['limit']){
                case 10:
                case 20:
                case 50:{
                    $limit = $get['limit'];
                }break;
            }
        }
        $offset = $current_page * $limit - $limit;
        $pdo = $GLOBALS['db']->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset, $limit");
        $pdo->execute();
        if($pdo->rowCount() > 0){
            $v = $pdo->fetchAll();
            $c = $offset;
            foreach($v as $item){
                $self = new self();
                $c++;
                $item['Index'] = $c;
                $return_items[] = $self->fill($item);
            }
        }
        return $return_items;
    }
}
?>