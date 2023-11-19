@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">FAQ</h2>

        <div class="accordion" id="faqAccordion">
            <!-- Question 1 -->
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What is ProjectSync?
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                    <div class="card-body">
                        ProjectSync is a project management tool that helps you organize and collaborate on projects efficiently.
                    </div>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How do I create a new project?
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                    <div class="card-body">
                        To create a new project, go to your dashboard and click on the "New Project" button. Fill in the required information and click "Create."
                    </div>
                </div>
            </div>

            <!-- Add more questions and answers as needed -->

        </div>
    </div>
@endsection
