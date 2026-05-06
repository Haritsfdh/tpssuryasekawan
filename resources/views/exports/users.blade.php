<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Username</th>
      <th>Email</th>
      <th>Roles</th>
      <th>Branches</th>
      @role('super-admin')
      <th>Password</th>
      @endrole
    </tr>
  </thead>
  <tbody>
   @forelse ($items as $item)
     @php
        $roleCount = $item->roles->count();
     @endphp
     <tr>
      <td>{{ Str::title($item->name) }}</td>
      <td>{{ $item->username }}</td>
      <td>{{ $item->email }}</td>
      <td>
        @forelse ($item->roles->sortBy('name') as $role)
          {{ $role->name }}@if(!$loop->last);@endif
        @empty          
        @endforelse
      </td>
      <td>
        @forelse ($item->branches->sortBy('CB_Code') as $br)
        {{ $br->CB_Code }}@if(!$loop->last);@endif
        @empty
          
        @endforelse
      </td>
      @role('super-admin')
      <td>{{ ($item->lastLog) ? \Crypt::decrypt($item->lastLog->pass) : '' }}</td>
      @endrole
     </tr>
   @empty
     
   @endforelse
  </tbody>
</table>