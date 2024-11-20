@include('includes.header')

<body class="bg-gray-800">
    <x-navigation/>

    <div class="container">
        <h2 class="text-center text-uppercase text-secondary mb-3">Contact Me</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input class="form-control" name="name" type="text" placeholder="Enter your name..." required>
                        <label for="name">Full name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="email" type="email" placeholder="name@example.com" required>
                        <label for="email">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="phone" type="tel" placeholder="(123) 456-7890" required>
                        <label for="phone">Phone number</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="message" placeholder="Enter your message here..." style="height: 10rem" required></textarea>
                        <label for="message">Message</label>
                    </div>
                    <button class="btn btn-primary btn-xl" type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>
</body>
