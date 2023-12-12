<div class="table-responsive">
 <table class="table table-bordered table-striped table-vcenter">
  <tr>
    <th width="5%">No</th>
    <th>Date</th>
    <th>Time</th>
    <th >User</th>
    <th width="40%">Properties</th>
    
  </tr>
  <tbody>
  @if(isset($data) && !$data->isEmpty())
  @foreach($data as $key=>$row)
  <?php
    $jsonData = json_decode($row->properties, true);
  ?>
  <tr>
   <td>{{ $data->firstItem() + $key }}</td>
   <td>{{ date_indo(date('Y-m-d',strtotime($row->created_at))) }}</td>
    <td>{{ date('H:i:s',strtotime($row->created_at)) }}</td>
   <td>{{ \App\Models\User::find($row->causer_id)->name }}</td>
   <td>
    <table border="0" style="font-size: 9pt">
      <tfoot>
      <tr>
        <td width="40%"><b>Access Type</b></td><td>{{$jsonData['attributes']['type']}}</td>
      </tr>
      @if(isset($jsonData['attributes']['description']))
      <tr>
        <td><b>Description</b></td><td>{{$jsonData['attributes']['description']}}</td>
      </tr>
      @endif
      @if(isset($jsonData['attributes']['menu']))
      <tr>
        <td><b>Menu</b></td><td>{{$jsonData['attributes']['menu']}}</td>
      </tr>
      @endif
      @if(isset($jsonData['attributes']['table']))
      <tr>
        <td><b>Table</b></td><td>{{$jsonData['attributes']['table']}}</td>
      </tr>
      @endif
      @if(isset($jsonData['attributes']['data']))
      <tr>
        <td><b>Data</b></td><td>
          {{
            array_walk($jsonData['attributes']['data'], function(&$value, $key) {
             echo "$key => $value<br />\n";
           })
        }}</td>
      </tr>
      @endif
       @if(isset($jsonData['attributes']['device']))
      <tr>
        <td><b>Device</b></td><td>
          {{$jsonData['attributes']['device']}}
      </td>
      </tr>
      @endif
      @if(isset($jsonData['attributes']['browser']))
      <tr>
        <td><b>Browser</b></td><td>
        {{$jsonData['attributes']['browser']}}
      </td>
      </tr>
      @endif
    </tfoot>
    </table>
   </td>
   
 </tr>
 @endforeach
 @else
 <tr>
  <td colspan="5" style="text-align: center">Data Kosong</td>
 </tr>
 @endif
</tbody>
</table>

<?php
// config
$link_limit = 8; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($data->lastPage() > 1)
<nav aria-label="Page navigation">
  <ul class="pagination pagination-sm">
    <li class="page-item {{ ($data->currentPage() == 1) ? ' disabled' : '' }}">
      <a class="page-link" href="{{ $data->url(1) }}">First</a>
    </li>
    @for ($i = 1; $i <= $data->lastPage(); $i++)
    <?php
    $half_total_links = floor($link_limit / 2);
    $from = $data->currentPage() - $half_total_links;
    $to = $data->currentPage() + $half_total_links;
    if ($data->currentPage() < $half_total_links) {
     $to += $half_total_links - $data->currentPage();
   }
   if ($data->lastPage() - $data->currentPage() < $half_total_links) {
    $from -= $half_total_links - ($data->lastPage() - $data->currentPage()) - 1;
  }
  ?>
  @if ($from < $i && $i < $to)
  <li class="page-item {{ ($data->currentPage() == $i) ? ' active' : '' }}">
    <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
  </li>
  @endif
  @endfor
  <li class="page-item {{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }}">
    <a class="page-link" href="{{ $data->url($data->lastPage()) }}">Last</a>
  </li>
</ul>
</nav>
@endif

</div>