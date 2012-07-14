
<div id="search_list"><!-- 搜索框 -->
<form action="<?php echo site_url("login_history");?>" method="get">
<ul>
<li class="day">
<select name="day">
<option value="today" selected>今天</option>
<option value="yesterday">昨天</option>
<option value="week">一周</option>
<option value="month">一月</option>
</select>
</li>

<li class="time_b">&nbsp;从
<select name="time_b"><option value="month">一月</option></select>
</li>

<li class="time_e">到
<select name="time_e"><option value="month">一月</option></select>
&nbsp;
</li>


<li class="os">操作系统
<select name="os">
<option value="all" selected>全部</option>
<option value="Windows">Windows</option>
<option value="Linux">Linux</option>
<option value="Android">Android</option>
</select>
</li>

<li class="search"><button type="submit">查询</button></li>

</ul>
</form>
</div><!-- 搜索框结束 -->
