@extends('main.layout.app')
@section('content')
    <!-- Hero Section -->

    <section class="body-font">
        <div class="flex items-center justify-center flex-col">
            <div class="hero_bg w-full"><img style="object-fit: contain;" src="{{ asset('assets/main/hero_bg.png') }}" alt="">
            </div>
            <div class="text-center  w-full px-5 absolute xl:w-2/3 xl:top-[30%] lg:top-56 top-24 sm:top-36">
                <p class="text-[#F8C204] text-sm sm:text-xl md:text-2xl lg:text-3xl">Join the Dairy Journey</p>
                <h1 class="title-font lg:text-6xl sm:text-4xl md:text-5xl mb-2 lg:mb-4 font-semibold text-white text-xl">
                    Where Happy Cows Create
                    Blissful Dairy Products</h1>
                <p class="mb-4 lg:mb-4  text-white text-sm lg:text-lg">Lorem ipsum dolor sit amet, consectetur adipiscing
                    elit. Ut elit tellus luctus nec ullamcorper mattis.</p>
                <div class="flex justify-center">
                    <button
                        class="inline-flex bg-[#F8C204] text-black items-center  border-0 py-1 lg:px-6 lg:py-2 px-4 focus:outline-none rounded-full text-sm lg:text-lg">Discover
                        More <i class='bx bx-right-arrow-alt lg:text-3xl text-xl'></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Hero Section -->

    <!-- About us Section -->

    <section class="xl:grid xl:grid-cols-2 flex flex-col gap-1 w-full lg:p-10 p-5 xl:h-[65vh]">
        <!-- about us photos section -->

        <div class="relative hidden xl:flex">
            <div class="flex gap-16 back_layer items-center">
                <div class="back_layer_img_1">
                    <img height="400" width="400" src="{{ asset('assets/main/About_1.png') }}" />
                </div>
                <div style="border: 2px dashed #0066B7;" class="rounded-full">
                    <div class="bg-[#0066B7] h-full p-1 m-3 rounded-full">
                        <img class="p-4" height="100" width="100" src="{{ asset('assets/main/cow_logo.png') }}" />
                    </div>
                </div>
            </div>
            <div class="front_layer absolute bottom-0 right-0">
                <img height="200" width="400" src="{{ asset('assets/main/About_2.png') }}" alt="">
            </div>
        </div>

        <!-- about us photos section -->

        <!-- about us detail section  -->

        <div class="flex flex-col gap-3 lg:gap-5 lg:px-10 justify-center items-center lg:items-start w-full">
            <p class="text-xl lg:text-2xl text-[#0066B7] font-medium">About Us</p>
            <h1 class="lg:text-6xl text-2xl text-center lg:text-left font-medium text-[#2C2C2C]">KNOW ABOUT OUR FARM &
                HISTORY</h1>
            <p class="text-lg text-[#2C2C2C] text-center lg:text-left">We hove been working in this industry for more than
                30 years with trust and honesty. All hands must be on deck if we are to achieve our goal of improving global
                nutrition.</p>
            <div class="flex gap-5 sm:gap-16 mt-4">
                <div class="flex flex-col gap-2">
                    <h3 class="text-xl font-medium text-[#2C2C2C] mb-2">OUR MISSION</h3>
                    <p class="flex items-center gap-1"><i class='bx bx-check text-xl'></i> High-quality work</p>
                    <p class="flex items-center gap-1"><i class='bx bx-check text-xl'></i>Straightforward pricing</p>
                    <p class="flex items-center gap-1"><i class='bx bx-check text-xl'></i> Rapid response times</p>
                </div>

                <div class="flex flex-col justify-center items-center p-2 lg:p-3 rounded-xl text-white bg-[#0066B7]">
                    <p><span class="text-6xl font-medium">12</span> +</p>
                    <p class="text-lg font-light">YEARS EXPERIENCE</p>
                </div>
            </div>
            <button style="border: 2px dashed #0066B7;"
                class="text-xl sm:w-[50%] lg:w-[30%] w-full flex justify-center items-center font-medium rounded-lg p-3 mt-2 text-[#0066B7]">GET
                IN TOUCH</button>
        </div>

        <!-- about us detail section  -->
    </section>

    <!-- About us Section -->


    <!-- our products section -->

    {{-- <section class="bg-[#0066B7] p-5 lg:p-12 w-full">
        <div class="w-full flex flex-col justify-center items-center lg:gap-8 gap-4">
            <div class="lg:w-[70%] flex flex-col items-center lg:gap-5 gap-2">
                <h3 class="text-xl lg:text-2xl xl:text-3xl text-[#F8C204] font-medium">Our Products</h3>
                <h1 class="xl:text-6xl md:text-4xl lg:text-5xl text-2xl font-medium text-white ">Organic Milk Products</h1>
                <p class="text-center text-white text-sm sm:text-lg">We hove been working in this industry for more than 30
                    years with trust and honesty. All hands must be on deck if we are to achieve our goal of improving
                    global nutrition.</p>
            </div>
            <div class="flex flex-wrap justify-center xl:flex-nowrap gap-5">
                <div class="relative xl:w-full w-[47%]">
                    <img class="h-full w-full" src="{{ asset('assets/main/product_1.png') }}" alt="">
                    <h1
                        class="absolute bottom-4 text-[1rem] sm:text-xl left-4 xl:text-2xl font-semibold xl:w-[40%] w-[70%]">
                        Cottage Cheese & Cream</h1>
                </div>
                <div class="relative xl:w-full w-[47%]">
                    <img class="h-full w-full" src="{{ asset('assets/main/product_2.png') }}" alt="">
                    <h1
                        class="absolute bottom-4 sm:text-xl left-4 xl:text-2xl font-semibold xl:w-[40%] w-[70%] text-[1rem]">
                        Organic Milk & kefir</h1>
                </div>
                <div class="relative xl:w-full w-[47%] ">
                    <img class="h-full w-full" src="{{ asset('assets/main/product_3.png') }}" alt="">
                    <h1
                        class="absolute bottom-4 sm:text-xl left-4 xl:text-2xl text-[1rem] font-semibold xl:w-[40%] w-[70%]">
                        Hard Cheese Products</h1>
                </div>

            </div>
        </div>
    </section> --}}

    <!-- our products section -->

    <!-- why to choose section  -->

    <section class="mt-8 lg:p-10 p-5 bg-[#F2F2F2] w-full">
        <h1 class="text-[#0066B7] lg:text-3xl text-xl font-medium text-center w-full lg:mb-12 mb-6">Why to choose us</h1>
        <div class="lg:grid lg:grid-cols-5 flex flex-col items-center gap-5">
            <div class="col-span-2">
                <div class="flex-col flex gap-4">
                    <div class="flex gap-3">
                        <div
                            class="text-lg text-white font-medium bg-[#0066B7] p-1 lg:h-full h-[25px] lg:h-full w-[90px] flex justify-center items-center number rounded-full">
                            1</div>
                        <div class="flex flex-col gap-2">
                            <div class="text-2xl heading font-medium">Organic and non-GMO</div>
                            <div class="">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis odio iure
                                aperiam magni neque eius, possimus molestias sed voluptatibus totam fuga harum minima
                                accusantium necessitatibus quae, accusamus, ad ullam molestiae!</div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div
                            class="text-lg text-white font-medium bg-[#F8C204] p-1 h-[25px] lg:h-full w-[90px] flex justify-center items-center number rounded-full ">
                            2</div>
                        <div class="flex flex-col gap-2">
                            <div class="text-2xl heading font-medium">Award wining quality</div>
                            <div class="">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis odio iure
                                aperiam magni neque eius, possimus molestias sed voluptatibus totam fuga harum minima
                                accusantium necessitatibus quae, accusamus, ad ullam molestiae!</div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div
                            class="text-lg text-white font-medium bg-[#0066B7] p-1 h-[25px] lg:h-full w-[90px] flex justify-center items-center number rounded-full">
                            3</div>
                        <div class="flex flex-col gap-2">
                            <div class="text-2xl heading font-medium">Best dairy products</div>
                            <div class="">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis odio iure
                                aperiam magni neque eius, possimus molestias sed voluptatibus totam fuga harum minima
                                accusantium necessitatibus quae, accusamus, ad ullam molestiae!</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-5"><img src="{{ asset('assets/main/why_to_choose.png') }}" alt=""></div>
            <div class="col-span-2">
                <div class="flex-col flex gap-4 lg:text-right">
                    <div class="flex gap-3">
                        <div
                            class="text-lg text-white font-medium bg-[#0066B7] p-1 h-[25px] lg:h-full w-[90px] flex justify-center items-center number rounded-full lg:order-1">
                            4</div>
                        <div class="flex flex-col gap-2">
                            <div class="text-2xl heading font-medium">Healthy and nutritious</div>
                            <div class="">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis odio iure
                                aperiam magni neque eius, possimus molestias sed voluptatibus totam fuga harum minima
                                accusantium necessitatibus quae, accusamus, ad ullam molestiae!</div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div
                            class="text-lg text-white font-medium bg-[#F8C204] p-1 h-[25px] lg:h-full w-[90px] flex justify-center items-center number rounded-full lg:order-1">
                            5</div>
                        <div class="flex flex-col gap-2">
                            <div class="text-2xl heading font-medium">500 acres of pasture</div>
                            <div class="">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis odio iure
                                aperiam magni neque eius, possimus molestias sed voluptatibus totam fuga harum minima
                                accusantium necessitatibus quae, accusamus, ad ullam molestiae!</div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div
                            class="text-lg text-white font-medium bg-[#0066B7] p-1 h-[25px] lg:h-full w-[90px] flex justify-center items-center number rounded-full lg:order-1">
                            6</div>
                        <div class="flex flex-col gap-2">
                            <div class="text-2xl heading font-medium">Delivery to your door</div>
                            <div class="">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis odio iure
                                aperiam magni neque eius, possimus molestias sed voluptatibus totam fuga harum minima
                                accusantium necessitatibus quae, accusamus, ad ullam molestiae!</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- why to choose section  -->

    <!-- special information section  -->

    <section class="lg:p-10 p-5">
        <div class="flex flex-col justify-center items-center gap-2">
            <h3 class="text-xl font-medium">Blog and News</h3>
            <h1 class="lg:text-5xl text-2xl text-[#0066B7] font-medium  text-center">Special Information</h1>
        </div>
        <div class="flex flex-col lg:flex-row mt-8 w-full">
            <div class="flex flex-wrap gap-6 lg:px-5 justify-center  w-full">
                <div class="flex relative flex-col w-full sm:w-[48%] lg:w-[24%]">
                    <img src="{{ asset('assets/main/special_info_1.png') }}" alt="">
                    <div class="bg-white flex flex-col p-4 gap-4 rounded-b-xl">
                        <h2 class="text-2xl text-[#0066B7]">Generations of Dedication to
                            Quality on Our Cow Fram</h2>
                        <div class="flex gap-5 items-center">
                            <p><i class="bx bx-user"></i> Admin</p>
                            <p> <i class="bx bx-calendar"></i> August 11,2023</p>
                        </div>
                        <p class="text-sm">Lorem ipsum sit amet, consectetur adipiscing
                            elit. Cras eu lectus nuce. Interdum et malesuada
                            fames ac ante ipsum primis in faucibus Class
                            aptent taciti sociosqu ad Iitora torquent per...</p>
                        <a href="#" class="text-[#0066B7]">Read More</a>
                    </div>
                    <div
                        class="flex justify-center items-center bg-[#0066B7] text-white rounded-full py-1 px-2 text-sm absolute top-3 left-3">
                        Information</div>
                </div>
                <div class="flex relative flex-col w-full lg:w-[24%] sm:w-[48%]">
                    <img src="{{ asset('assets/main/special_info_2.png') }}" alt="">
                    <div class="bg-white flex flex-col p-4 gap-4 rounded-b-xl">
                        <h2 class="text-2xl text-[#0066B7]">Generations of Dedication to
                            Quality on Our Cow Fram</h2>
                        <div class="flex gap-5 items-center">
                            <p><i class="bx bx-user"></i> Admin</p>
                            <p> <i class="bx bx-calendar"></i> August 11,2023</p>
                        </div>
                        <p class="text-sm">Lorem ipsum sit amet, consectetur adipiscing
                            elit. Cras eu lectus nuce. Interdum et malesuada
                            fames ac ante ipsum primis in faucibus Class
                            aptent taciti sociosqu ad Iitora torquent per...</p>
                        <a href="#" class="text-[#0066B7]">Read More</a>
                    </div>
                    <div
                        class="flex justify-center items-center bg-[#0066B7] text-white rounded-full py-1 px-2 text-sm absolute top-3 left-3">
                        Information</div>
                </div>
                <div class="flex relative flex-col w-full lg:w-[24%] sm:w-[48%]">
                    <img src="{{ asset('assets/main/special_info_3.png') }}" alt="">
                    <div class="bg-white flex flex-col p-4 gap-4 rounded-b-xl">
                        <h2 class="text-2xl text-[#0066B7]">Generations of Dedication to
                            Quality on Our Cow Fram</h2>
                        <div class="flex gap-5 items-center">
                            <p><i class="bx bx-user"></i> Admin</p>
                            <p> <i class="bx bx-calendar"></i> August 11,2023</p>
                        </div>
                        <p class="text-sm">Lorem ipsum sit amet, consectetur adipiscing
                            elit. Cras eu lectus nuce. Interdum et malesuada
                            fames ac ante ipsum primis in faucibus Class
                            aptent taciti sociosqu ad Iitora torquent per...</p>
                        <a href="#" class="text-[#0066B7]">Read More</a>
                    </div>
                    <div
                        class="flex justify-center items-center bg-[#0066B7] text-white rounded-full py-1 px-2 text-sm absolute top-3 left-3">
                        Information</div>
                </div>
                <div class="flex flex-col lg: lg:w-[20%] w-full gap-6 p-2 sm:w-[48%]">
                    <h1 class="text-2xl font-medium text-[#0066B7]">Tips & Trick</h1>
                    <p>Lorem ipsum dolr sit amet, consectetur
                        adipiscing elit.</p>
                    <div class="flex flex-col gap-1">
                        <p class="text-[#0066B7]">Generations of Dedication to
                            Quality on Our Cow Farm</p>
                        <p><i class="bx bx-calendar"></i> August 11,2023</p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-[#0066B7]">Generations of Dedication to
                            Quality on Our Cow Farm</p>
                        <p><i class="bx bx-calendar"></i> August 11,2023</p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-[#0066B7]">Generations of Dedication to
                            Quality on Our Cow Farm</p>
                        <p><i class="bx bx-calendar"></i> August 11,2023</p>
                    </div>
                    <button
                        class="bg-[#0066B7] flex justify-center items-center rounded-full text-white text-xl px-5 py-2 w-[50%]">Read
                        More</button>
                </div>
            </div>

        </div>
    </section>

    <!-- special information section  -->

    <!-- testimonials section  -->

    <section class="lg:py-12 py-6 px-5 lg:px-10 bg-[#F4EFE3] z-[-1] ">
        <div class="flex flex-col items-center lg:gap-16 gap-8  ">
            <div class="testimonials_profiles flex relative justify-between items-center  w-[70%] ">
                <div
                    class="profile_1 w-16 h-16 lg:w-[175px] lg:h-[175px] xl:w-[250px] xl:h-[250px] overflow-hidden z-[2] rounded-full">
                    <img class="h-full w-full" src="{{ asset('assets/main/profile_1.png') }}" alt="">
                </div>
                <div
                    class="profile_1 w-16 h-16 lg:w-[175px] lg:h-[175px] xl:w-[250px] xl:h-[250px] overflow-hidden z-[2] border_profile rounded-full">
                    <img class="h-full w-full" src="{{ asset('assets/main/Profile_2.png') }}" alt="">
                </div>
                <div
                    class="profile_1 w-16 h-16 lg:w-[175px] lg:h-[175px] xl:w-[250px] xl:h-[250px] overflow-hidden z-[2] rounded-full">
                    <img class="w-full h-full" src="{{ asset('assets/main/Profile_3.png') }}" alt="">
                </div>
                <div class="bg_line bg-[#0066B7] h-[2px] w-full absolute z-[0]"></div>
            </div>
            <div class="flex justify-between w-full ">
                <div class="w-[10%] text-left"><i class='bx bx-left-arrow-circle text-2xl lg:text-5xl'></i></div>
                <div class=" w-full text-center flex relative h-44">
                    <div class="flex flex-col gap-2 lg:gap-8 w-full absolute">
                        <p class="lg:text-lg text-xs p-2">From crispy and golden fries to mouthwatering burgers and wraps,
                            our menu offers a variety of fast-food favorites. Each item is crafted with quality ingredients
                            to ensure a tasty experience with every order. Enjoy your quick meal in a casual and friendly
                            setting. our welcoming atmosphere makes every visit enjoyable.</p>
                        <p class="lg:text-xl text-sm text-[#0066B7] font-medium">Profile 1</p>
                    </div>
                    <div class=" flex-col gap-8 w-full absolute hidden">
                        <p class="text-lg">From crispy and golden fries to mouthwatering burgers and wraps, our menu offers
                            a variety of fast-food favorites. Each item is crafted with quality ingredients to ensure a
                            tasty experience with every order. Enjoy your quick meal in a casual and friendly setting. our
                            welcoming atmosphere makes every visit enjoyable.</p>
                        <p class="text-xl text-[#0066B7] font-medium">Profile 1</p>
                    </div>
                    <div class=" flex-col gap-8 w-full absolute hidden">
                        <p class="text-lg">From crispy and golden fries to mouthwatering burgers and wraps, our menu offers
                            a variety of fast-food favorites. Each item is crafted with quality ingredients to ensure a
                            tasty experience with every order. Enjoy your quick meal in a casual and friendly setting. our
                            welcoming atmosphere makes every visit enjoyable.</p>
                        <p class="text-sm lg:text-xl text-[#0066B7]  font-medium">Profile 1</p>
                    </div>

                </div>
                <div class="w-[10%] text-right"><i class='bx bx-right-arrow-circle text-2xl lg:text-5xl'></i></div>
            </div>
    </section>

    <!-- testimonials section  -->

    <!-- accordion section  -->

    <section class="lg:p-10 p-5">
        <div class="flex flex-col gap-12">
            <div class="relative w-full flex flex-col items-center lg:block">
                <h1 class="lg:text-4xl text-2xl font-medium text-[#0066B7] text-center lg:text-left">
                    {{-- Frequently Asked
                    Questions --}}
                    FAQs
                </h1>
                <p class="lg:h-[4px] h-[2px] lg:w-[10%] w-[30%] bottom-[-10px] bg-[#0066B7] absolute"></p>
            </div>
            <div class="flex flex-col gap-4" id="accordion-collapse" data-accordion="collapse">
                <div class="flex rounded-lg overflow-hidden">
                    <div
                        class="bg-[#0066B7] py-1 px-2 lg:py-2 lg:px-4  xl:text-2xl text-xl text-white flex justify-center items-center font-medium">
                        1</div>
                    <div class="w-full">
                        <h2 id="accordion-collapse-heading-1">
                            <button type="button"
                                class="flex items-center !text-black bg-white justify-between w-full lg:p-4 p-2 font-medium text-sm md:text-lg lg:text-xl rtl:text-right gap-3"
                                data-accordion-target="#accordion-collapse-body-1" aria-expanded="true"
                                aria-controls="accordion-collapse-body-1">
                                <span class="text-left">Lorem Ipsum is simply dummy text of the printing and typesetting
                                    industry.</span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M9 5 5 1 1 5" />
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-collapse-body-1" class="hidden"
                            aria-labelledby="accordion-collapse-heading-1">
                            <div class="lg:p-4 p-2  bg-white ">
                                <p class="mb-2 lg:text-lg text-sm lg:w-[70%]">Lorem Ipsum has been the industry's standard
                                    dummy text ever since the 1500s, when an unknown printer took a galley of type and
                                    scrambled it to make a type specimen book. It has survived not only five centuries, but
                                    also the leap into electronic typesetting, remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum.</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex rounded-lg overflow-hidden">
                    <div
                        class="bg-[#0066B7] py-1 px-2 lg:py-2 lg:px-4 xl:text-2xl text-xl text-white flex justify-center items-center font-medium">
                        2</div>
                    <div class="w-full">
                        <h2 id="accordion-collapse-heading-2">
                            <button type="button"
                                class="flex items-center !text-black bg-white justify-between w-full lg:p-4 p-2 font-medium text-sm md:text-lg xl:text-xl rtl:text-right gap-3"
                                data-accordion-target="#accordion-collapse-body-2" aria-expanded="true"
                                aria-controls="accordion-collapse-body-2">
                                <span class="text-left ">Lorem Ipsum is simply dummy text of the printing and typesetting
                                    industry.</span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M9 5 5 1 1 5" />
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-collapse-body-2" class="hidden"
                            aria-labelledby="accordion-collapse-heading-2">
                            <div class="lg:p-4 p-2  bg-white ">
                                <p class="mb-2 lg:text-lg text-sm lg:w-[70%]">Lorem Ipsum has been the industry's standard
                                    dummy text ever since the 1500s, when an unknown printer took a galley of type and
                                    scrambled it to make a type specimen book. It has survived not only five centuries, but
                                    also the leap into electronic typesetting, remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum.</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex rounded-lg overflow-hidden">
                    <div
                        class="bg-[#0066B7] py-1 px-2 lg:py-2 lg:px-4 xl:text-2xl text-xl text-white flex justify-center items-center font-medium">
                        3</div>
                    <div class="w-full">
                        <h2 id="accordion-collapse-heading-3">
                            <button type="button"
                                class="flex items-center !text-black bg-white justify-between w-full lg:p-4 p-2 font-medium text-sm md:text-lg lg:text-xl rtl:text-right gap-3"
                                data-accordion-target="#accordion-collapse-body-3" aria-expanded="true"
                                aria-controls="accordion-collapse-body-3">
                                <span class="text-left">Lorem Ipsum is simply dummy text of the printing and typesetting
                                    industry.</span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M9 5 5 1 1 5" />
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-collapse-body-3" class="hidden"
                            aria-labelledby="accordion-collapse-heading-3">
                            <div class="lg:p-4 p-2  bg-white ">
                                <p class="mb-2 lg:text-lg text-sm lg:w-[70%]">Lorem Ipsum has been the industry's standard
                                    dummy text ever since the 1500s, when an unknown printer took a galley of type and
                                    scrambled it to make a type specimen book. It has survived not only five centuries, but
                                    also the leap into electronic typesetting, remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum.</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex rounded-lg overflow-hidden">
                    <div
                        class="bg-[#0066B7]  py-1 px-2 lg:py-2 lg:px-4 xl:text-2xl text-xl text-white flex justify-center items-center font-medium">
                        4</div>
                    <div class="w-full">
                        <h2 id="accordion-collapse-heading-1">
                            <button type="button"
                                class="flex items-center !text-black bg-white justify-between w-full lg:p-4 p-2 font-medium text-sm md:text-lg lg:text-xl rtl:text-right gap-3"
                                data-accordion-target="#accordion-collapse-body-4" aria-expanded="true"
                                aria-controls="accordion-collapse-body-4">
                                <span class="text-left">Lorem Ipsum is simply dummy text of the printing and typesetting
                                    industry.</span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M9 5 5 1 1 5" />
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-collapse-body-4" class="hidden"
                            aria-labelledby="accordion-collapse-heading-4">
                            <div class="lg:p-4 p-2  bg-white ">
                                <p class="mb-2 lg:text-lg text-sm lg:w-[70%]">Lorem Ipsum has been the industry's standard
                                    dummy text ever since the 1500s, when an unknown printer took a galley of type and
                                    scrambled it to make a type specimen book. It has survived not only five centuries, but
                                    also the leap into electronic typesetting, remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum.</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex rounded-lg overflow-hidden">
                    <div
                        class="bg-[#0066B7]  py-1 px-2 lg:py-2 lg:px-4 xl:text-2xl text-xl text-white flex justify-center items-center font-medium">
                        5</div>
                    <div class="w-full">
                        <h2 id="accordion-collapse-heading-5">
                            <button type="button"
                                class="flex items-center !text-black bg-white justify-between w-full lg:p-4 p-2 font-medium text-sm md:text-lg lg:text-xl rtl:text-right gap-3"
                                data-accordion-target="#accordion-collapse-body-5" aria-expanded="true"
                                aria-controls="accordion-collapse-body-5">
                                <span class="text-left">Lorem Ipsum is simply dummy text of the printing and typesetting
                                    industry.</span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M9 5 5 1 1 5" />
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-collapse-body-5" class="hidden"
                            aria-labelledby="accordion-collapse-heading-5">
                            <div class="lg:p-4 p-2  bg-white ">
                                <p class="mb-2 text-sm lg:text-lg lg:w-[70%]">Lorem Ipsum has been the industry's standard
                                    dummy text ever since the 1500s, when an unknown printer took a galley of type and
                                    scrambled it to make a type specimen book. It has survived not only five centuries, but
                                    also the leap into electronic typesetting, remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum.</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- accordion section  -->

    <!-- stay tuned section  -->
    <section class=" relative lg:p-10 p-5">
        <div class="flex flex-col w-full lg:gap-5 gap-2 justify-center items-center">
            <div class="lg:text-4xl text-2xl text-black font-medium ">Stay Tuned</div>
            <p class="lg:w-[60%] text-sm lg:text-lg text-center">We hove been working in this industry for more than 30
                years with trust and honesty. All hands must be on deck if we are to achieve our goal of improving global
                nutrition.</p>
        </div>
        <div class="container flex-wrap lg:flex-nowrap gap-5  lg:px-5 lg:mt-16 mt-5 flex ">
            <div class="md:w-[48%] w-full md:w-1/2 overflow-hidden  flex  justify-start relative">
                <div class="flex flex-col lg:gap-8 gap-3 w-full">
                    <p class="lg:w-[60%] w-full">Contrary to popular belief Lorem Ipsum is not simply
                        random text. If has roots in a piece of classical Latin
                        literature form 45 BC, making it over 2000 years old</p>
                    <div class="flex lg:gap-4 gap-2 items-center">
                        <i class="bx bxs-home text-xl bg-[#0066B7] text-white p-2"></i>
                        <p class="lg:w-[60%]">Vestibulum nulla libero, convallis, tincidunt suscipit diam, DC 2002</p>
                    </div>
                    <div class="flex lg:gap-4 gap-2 items-center">
                        <i class="bx bxs-phone text-xl bg-[#0066B7] text-white p-2"></i>
                        <p class="lg:w-[60%]">+1230 456 789-012 345 6789</p>
                    </div>
                    <div class="flex lg:gap-4 gap-2 items-center">
                        <i class="bx bxs-envelope text-xl bg-[#0066B7] text-white p-2"></i>
                        <p class="lg:w-[60%]">exampledomain@domain.com</p>
                    </div>
                </div>

            </div>
            <div class="md:w-[48%] md:w-1/2 flex flex-col w-full ">
                <div class="relative mb-4">

                    <input type="text" id="name" placeholder="Name" name="name"
                        class="w-full bg-white !text-black  outline-none  py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
                <div class="relative mb-4">

                    <input placeholder="Email" type="email" id="email" name="email"
                        class="w-full bg-white  outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
                <div class="relative mb-4">

                    <input type="text" id="subject" placeholder="Subject" name="subject"
                        class="w-full bg-white outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
                <div class="relative mb-4">

                    <textarea rows="5" id="message" name="message" placeholder="Message"
                        class="w-full bg-white outline-none text-black py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                </div>

                <button class="text-white bg-[#0066B7] border-0 py-2 px-6 rounded text-lg"><i
                        class='bx rotate-[-45deg] bxs-send'></i> Send Message</button>
            </div>
        </div>
        </div>
    </section>
    <!-- stay tuned section  -->
@endsection
