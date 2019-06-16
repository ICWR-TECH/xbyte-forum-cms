<?php
// Simfor ( Simple Forum CMS )
// Copyright (c)2019 - Afrizal F.A - ICWR-TECH
include("conf.php");
session_start();
if($_GET['logout'] == "true") {
    session_destroy();
    header("location:index.php");
    exit;
}
?>
<html>
<!-- Theme & CMS By ICWR-TECH -->
<head>
    <title>X-Byte Forum</title>
    <meta name="description" content="Hacking Forum">
    <xlink href="https://fonts.googleapis.com/css?family=News%20Cycle" rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
if($_SESSION['aktif'] == "non") {
?>
Your Account Not Actived, <a href="?logout=true">Logout</a>
<?php
exit;
}
?>
<?php
if($_GET['page'] == "activation") {
$query_aktif_akun="SELECT * FROM pengguna WHERE username='$_GET[user]' AND status='$_GET[code]'";
if(mysqli_query($konek, $query_aktif_akun)) {
    $query_ganti_status_akun="UPDATE pengguna SET status='aktif' WHERE username='$_GET[user]'";
    if(mysqli_query($konek, $query_ganti_status_akun)) {
        echo "Your Account Is Actived <a href='?'>Click For Login</a>";
    }
}
}
?>
<div class="total">
    <div class="judul">
        <font class="font-judul"><?php echo $judul; ?></font>
        <br><br>
        <font class="font-bawah-judul"><?php echo $deskripsi; ?></font>
    </div>
    <div class="menu">
        <li><a href="?">Home</a></li>
<?php
if($_SESSION['login'] == "logged") {
?>
        <li><a href="?page=chat">Chat</a></li>
        <li><a href="?page=profile&user=<?php echo $_SESSION['username']; ?>">Profile</a></li>
<?php
}
?>
        <li><a href="?page=about">About</a></li>
        <li><a href="?page=disc">Disclaimer</a></li>
<?php
if($_SESSION['login'] == "logged") {
?>
        <li><a href="?logout=true">Logout</a></li>
<?php
}
?>
    </div>
    <div class="tengah">
        <div class="kiri">
<?php
if(!$_GET){
?>
            <table width="100%">
                <tr>
                    <td class="judul-konten" width="70%">Category</td>
                    <td class="judul-konten">Detail</td>
                </tr>
<?php
$query_kategori=mysqli_query($konek, "SELECT * FROM kategori ORDER BY id DESC");
while($data_kategori = mysqli_fetch_assoc($query_kategori)) {
?>
                <tr>
                    <td class="isi-konten" width="70%"><a href="?page=topic&cat_id=<?php echo $data_kategori['id']; ?>"><?php echo $data_kategori['judul']; ?></a></td>
                    <td class="isi-konten"><?php echo $data_kategori['detail']; ?></td>
                </tr>
<?php
}
?>
            </table>
<?php
}
?>
<?php
if($_GET['page'] == "topic") {
$get_kategori=mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM kategori WHERE id='$_GET[cat_id]'"));
if(!$_GET['view'] && !$_GET['new']) {
?>
<?php
if($_SESSION['login'] == "logged") {
?>
            <div class="tambah-topik">
                <a href="?page=topic&cat_id=<?php echo $get_kategori['id']; ?>&new=true">Add Topic</a>
            </div>
<?php
}
?>
            <table width="100%">
                <tr>
                    <td class="judul-konten" width="70%">Topic From, <?php echo $get_kategori['judul']; ?></td>
                    <td class="judul-konten">Date</td>
                </tr>
<?php
$topik_query=mysqli_query($konek, "SELECT * FROM topik WHERE id_kategori='$_GET[cat_id]' ORDER BY id DESC");
while($data_topik = mysqli_fetch_assoc($topik_query)) {
?>
                <tr>
                    <td class="isi-konten" width="70%"><a href="?page=topic&cat_id=<?php echo $data_topik['id_kategori']; ?>&view=<?php echo $data_topik['id']; ?>"><?php echo $data_topik['judul']; ?></a></td>
                    <td class="isi-konten"><?php echo $data_topik['tgl']; ?></td>
                </tr>
<?php
}
?>
            </table>
<?php
}
?>
<?php
if($_GET['new'] == "true") {
if($_SESSION['login'] == "logged") {
?>
            <div class="tambah-topik">
                <font size="20">Add Topic</font>
                <br><br>
                <form enctype="multipart/form-data" method="post">
                    Topic : <input type="text" name="judul">
                    <br><br>
                    Detail :
                    <br><br>
                    <textarea class="detail" name="detail"></textarea>
                    <br><br>
                    <input type="submit" name="tambah_topik" value="Add Topic">
                </form>
<?php
if($_POST['tambah_topik']) {
    $detail=str_replace("\n", "<br>", $_POST['detail']);
    $query_tambah_topic=mysqli_query($konek, "INSERT INTO topik(judul, detail, id_kategori, tgl, username) VALUES('$_POST[judul]', '$detail', '$_GET[cat_id]', '$tgl_waktu', '$_SESSION[username]')");
    if($query_tambah_topic) {
?>
                <br>Topic Added !!<br>
<?php
    }
}
?>
            </div>
<?php
} else {
?>
            <div class="tambah-topik">
                <center>You Need Login For Access</center>
            </div>
<?php
}
}
?>
<?php
if($_GET['view']) {
if($_SESSION['login'] == "logged") {
$get_topik=mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM topik WHERE id='$_GET[view]'"));
?>
            <div class="topik">
                <a href="?page=topic&cat_id=<?php echo $get_topik['id_kategori']; ?>&view=<?php echo $get_topik['id']; ?>"><font size="20"><?php echo $get_topik['judul']; ?></font></a>
                <hr>
                Category <a href="?page=topic&cat_id=<?php echo $get_kategori['id']; ?>"><?php echo $get_kategori['judul']; ?></a> By, <a href="?page=profile&user=<?php echo $get_topik['username']; ?>"><?php echo $get_topik['username']; ?></a> ( <?php echo $get_topik['tgl']; ?> )
                <hr>
                <?php echo $get_topik['detail']; ?>
                <br><br>
            </div>
            <div class="komentar">
                <font size="5">Reply</font>
                <hr>
<?php
$query_reply=mysqli_query($konek, "SELECT * FROM reply WHERE id_topik='$_GET[view]' ORDER BY id DESC");
while($data_reply = mysqli_fetch_assoc($query_reply)) {
?>
                <img height="30" width="30" src="<?php echo $data_reply['foto']; ?>"/> <a href="?page=profile&user=<?php echo $data_reply['username']; ?>"><?php echo $data_reply['username']; ?></a> ( <?php echo $data_reply['tgl']; ?> )
                <br><br>
                <?php echo $data_reply['reply']; ?>
                <br>
                <hr>
<?php
}
?>
                <form enctype="multipart/form-data" method="post">
                    Reply :
                    <br><br>
                    <textarea class="reply" name="reply"></textarea>
                    <br><br>
                    <input type="submit" name="komentar" value="Reply">
                </form>
<?php
if($_POST["komentar"]) {
    $reply_post=str_replace("\n", "<br>", $_POST['reply']);
    $query_kirim_reply=mysqli_query($konek, "INSERT INTO reply(username, reply, tgl, id_topik) VALUES('$_SESSION[username]', '$reply_post', '$tgl_waktu', '$_GET[view]')");
    if($query_kirim_reply) {
?>
                    <br>Reply Sended !!<br>
                    <script>window.location='<?php echo $_SERVER['REQUEST_URI']; ?>'</script>
<?php
    }
}
?>
            </div>
<?php
} else {
?>
<?php
if($_GET['view']) {
?>
            <div class="topik">
                <center>You Need Login For Access</center>
            </div>
<?php
}
?>
<?php
}
}
}
?>
<?php
if($_GET['page'] == "profile") {
?>
<?php
$get_pengguna=mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM pengguna WHERE username='$_GET[user]'"));
?>
            <div class="profil">
                <a href="?page=profile&user=<?php echo $get_pengguna['username']; ?>"><font size="20"><?php echo $get_pengguna['username']; ?></font></a>
                <br><br>
                <img width="200" src="<?php echo $get_pengguna['foto']; ?>"/>
                <br><br>
                Email : <?php echo $get_pengguna['email']; ?>
                <br><br>
                <a href="?page=chat&user=<?php echo $_GET[user]; ?>">Chat User</a>
            </div>
<?php
}
?>
<?php
if($_GET['page'] == "chat") {
?>
            <div class="chat">
<?php
if(!$_GET['user']) {
?>
                <font size="5">Chatting</font>
<br>
<hr>
<br>
<?php
$query_chat=mysqli_query($konek, "SELECT * FROM pesan WHERE to_user='$_SESSION[username]' ORDER BY id DESC LIMIT 0, 10");
while($data_chat = mysqli_fetch_array($query_chat)) {
?>
                <a href="?page=chat&user=<?php echo $data_chat['from_user']; ?>"><b><?php echo $data_chat['from_user']; ?></b></a> : <?php echo $data_chat['pesan']; ?>
                <hr>
<?php
}
}
?>
<?php
if($_GET['user']) {
?>
                <font size="5">Chat From, <?php echo $_GET['user']; ?></font>
                <hr>
<?php
$query_direct_chat=mysqli_query($konek, "SELECT * FROM pesan WHERE to_user='$_SESSION[username]' OR from_user='$_SESSION[username]' ORDER BY id ASC");
while($data_direct_chat = mysqli_fetch_array($query_direct_chat)) {
?>
<?php
if($data_direct_chat['from_user'] == $_SESSION['username']) {
if($_GET['user'] == $_SESSION['username']) {
?>
                <a href="?page=profile&user=<?php echo $data_direct_chat['to_user']; ?>"><b><?php echo $data_direct_chat['to_user']; ?></b></a> : <?php echo $data_direct_chat['pesan']; ?><hr>
<?php
}
}
?>
                <a href="?page=profile&user=<?php echo $data_direct_chat['from_user']; ?>"><b><?php echo $data_direct_chat['from_user']; ?></b></a> : <?php echo $data_direct_chat['pesan']; ?><hr>
<?php
}
?>
                <form enctype="multipart/form-data" method="post">
                    <input size="70" type="text" name="chat">
                    <input type="submit" name="send_chat" value="Send">
                </form>
<?php
if($_POST['send_chat']) {
$msg_chat=str_replace("\n", "<br>", $_POST['chat']);
$query_kirim_chat="INSERT INTO pesan(from_user, to_user, pesan) VALUES('$_SESSION[username]', '$_GET[user]', '$msg_chat')";
if(mysqli_query($konek, $query_kirim_chat)) {
?>
                <br>Msg Sended<br>
                <script>window.location='<?php echo $_SERVER['REQUEST_URI']; ?>'</script>
<?php
}
}
?>
<?php
}
?>
            </div>
<?php
}
?>
<?php
if($_GET['page'] == "about") {
?>
<h1>Hacker Forum</h1>
This A Hacker Forum
<?php
}
?>
<?php
if($_GET['page'] == "register") {
if(!$_SESSION['login'] == "logged") {
?>
<h1>Register</h1>
<form enctype="multipart/form-data" method="post">
    Username : <input type="text" name="username">
    <br><br>
    URL Photo : <input type="text" name="foto">
    <br><br>
    Email : <input type="email" name="email">
    <br><br>
    Password : <input type="password" name="password">
    <br><br>
    <input type="submit" name="daftar" value="Register">
</form>
<?php
if($_POST['daftar']) {
if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
$cek_daftar=mysqli_query($konek, "SELECT * FROM pengguna WHERE username='$_POST[username]' OR email='$_POST[email]'");
if(mysqli_num_rows($cek_daftar) > 0 ) {
?>
<br>Sorry Username Or Email Is Already Taken<br>
<?php
} else {
if(empty($_POST['foto'])) {
$foto_daftar="$site/default.jpg";
} else {
$foto_daftar=$_POST['foto'];
}
$username=mysqli_real_escape_string($konek, $_POST['username']);
$password_daftar=md5($_POST['password']);
$aktif_kode=rand(1000000000, 9999999999);
$query_daftar="INSERT INTO pengguna(username, email, password, foto, tgl_daftar, level, total, status) VALUES('$username', '$_POST[email]', '$password_daftar', '$foto_daftar', '$tgl_waktu', 'user', '0', '$aktif_kode')";
if(mysqli_query($konek, $query_daftar)) {
mail($_POST['email'], "$judul, Activation Code", "Hallo, $_POST[username]\nLink For Activation Your Account, $site/?page=activation&user=$username&code=$aktif_kode");
?>
<br>Register Success, <?php echo $_POST['username']; ?></br>
<?php
} else {
?>
<br>Register Failed</br>
<?php
}
}
}
}
}
}
?>
<?php
if($_GET['page'] == "disc") {
?>
<h1>Disclaimer</h1>
We Don't Care
<?php
}
?>
        </div>
        <div class="kanan">
            <table width="100%">
<?php
if(!$_SESSION['login'] == "logged") {
?>
                <form enctype="multipart/form-data" method="post">
                    <font size="5">Login</font>
                    <br><br>
<?php
if($_POST['login']) {
$email = mysqli_real_escape_string($konek, $_POST['email']);
$pass = md5($_POST['passwd']);
$data = mysqli_query($konek,"SELECT * FROM pengguna WHERE email='$email' AND password='$pass'");
$cek_status = mysqli_fetch_array(mysqli_query($konek,"SELECT * FROM pengguna WHERE email='$email'"));
$cek = mysqli_num_rows($data);
    if($cek > 0){
        $_SESSION['login'] = "logged";
        $_SESSION['username'] = $cek_status['username'];
        if(!$cek_status['status'] == "aktif") {
            $_SESSION['aktif'] = "non";
        }
        header("location:index.php");
    }else{
        header("location:index.php?login=true");
    }
}
?>
                    Email :
                    <br>
                    <input type="email" name="email">
                    <br><br>
                    password :
                    <br>
                    <input type="password" name="passwd">
                    <br><br>
                    <input type="submit" name="login" value="Login">
                    <br><br>
                    <a href="?page=register">Register</a>
                </form>
                <br><br>
<?php
}
?>
                <tr>
                    <td class="judul-konten">News Topic</td>
                </tr>
                <tr>
<?php
$topik_baru_query=mysqli_query($konek, "SELECT * FROM topik ORDER BY id DESC LIMIT 0, 5");
while($data_topik_baru = mysqli_fetch_assoc($topik_baru_query)) {
?>
                <tr>
                    <td class="isi-konten" width="70%"><a href="?page=topic&cat_id=<?php echo $data_topik_baru['id_kategori']; ?>&view=<?php echo $data_topik_baru['id']; ?>"><?php echo $data_topik_baru['judul']; ?></a></td>
                </tr>
<?php
}
?>
                </tr>
            </table>
        </div>
    </div>
    <div class="copyleft">
        Copyleft &copy;2019 - Unreserved - ICWR-TECH
    </div>
</div>
</body>
</html>