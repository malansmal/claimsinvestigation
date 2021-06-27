<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>
</head>

<body>
<a href="http://www.shalesh.co.za/" target="_blank" class="newWindow">Play Track</a>
<script>
$(document).ready(function(){
    $(".newWindow").click(function(e){
        e.preventDefault(); // this will prevent the browser to redirect to the href
        // if js is disabled nothing should change and the link will work normally
        var url = $(this).attr('href');
        var windowName = $(this).attr('id');
        window.open(url, windowName, "height=800,width=600,scrollbars=yes");
    });
});

</script>
</body>
</html>
