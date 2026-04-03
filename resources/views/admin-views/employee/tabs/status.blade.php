<div class="card">
    <div class="card-body">

        <h4 class="mb-3">Profile Status</h4>

        <form action="" method="POST">
            @csrf

            <div class="d-flex gap-3">
                <label><input type="radio" name="profile_status" value="0" {{ $assessor->profile_status==0?'checked':'' }}> Pending</label>
                <label><input type="radio" name="profile_status" value="1" {{ $assessor->profile_status==1?'checked':'' }}> Approve</label>
                <label><input type="radio" name="profile_status" value="2" {{ $assessor->profile_status==2?'checked':'' }}> Reject</label>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">Update Status</button>
            </div>
        </form>

    </div>
</div>
