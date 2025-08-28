<footer class="bg-indigo-950 text-indigo-100 py-10 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <!-- Logo / About -->
            <div>
                <h2 class="text-2xl font-bold text-white">LiTrain</h2>
                <p class="mt-3 text-sm text-indigo-200">
                    Learn, train, and improve your reading & writing skills with books in different languages.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-3">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="hover:text-white transition">Home</a></li>
                    <li><a href="/books" class="hover:text-white transition">Books</a></li>
                    <li><a href="/training" class="hover:text-white transition">Training</a></li>
                    <li><a href="/publisher/request" class="hover:text-white transition">Become Publisher</a></li>
                    <li><a href="/profile" class="hover:text-white transition">Profile</a></li>
                </ul>
            </div>

            <!-- Languages -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-3">Languages</h3>
                <form action="" method="POST">
                    @csrf
                    <select name="language" onchange="this.form.submit()"
                        class="w-full bg-indigo-950 border border-indigo-500 text-white rounded-lg p-2 focus:ring-2 focus:ring-white focus:border-white">
                        <option value="en">English</option>
                        <option value="ar">العربية</option>
                        <option value="fr">Français</option>
                        <!-- Add more -->
                    </select>
                </form>
            </div>

            <!-- Social Media -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-3">Follow Us</h3>
                <div class="flex space-x-4 text-xl">
                    <a href="#" class="hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-indigo-500 mt-8 pt-6 text-center text-sm text-indigo-200">
            © {{ date('Y') }} LiTrain. All rights reserved.
        </div>
    </div>
</footer>
