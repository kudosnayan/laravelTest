<a href="{{ url('/open-google-sheet/' . 1) }}" target="_blank">Open Google Sheet</a>

<form method="post" action="{{ url('/user/store') }}">
    @csrf
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <!-- Add other form fields as needed -->

    <button type="submit">Submit</button>
</form>
