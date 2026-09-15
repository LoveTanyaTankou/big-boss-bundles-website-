/* =========================================================
   BIG BOSS BEAUTY ACADEMY
   CLASS SCHEDULE & PRICING

   Edit this file whenever you want to:
   - Change a course date
   - Change a course
   - Change prices
   - Change available seats
   - Mark a class sold out
   - Close registration

   STANDARD CLASS TIME:
   12:00 PM - 4:30 PM

   MAXIMUM STUDENTS:
   7 per class
========================================================= */


/* =========================================================
   ACADEMY SETTINGS
========================================================= */

const academySettings = {

  academyName: "Big Boss Beauty Academy",

  classTime: "12:00 PM – 4:30 PM",

  duration: "4.5 Hours",

  maxStudents: 7,

  youthMinAge: 10,

  youthMaxAge: 16,

  adultMinAge: 17,

  suppliesIncluded: true,

  mannequinIncluded: true,

  certificateIncluded: true,

  waitingListEnabled: true

};


/* =========================================================
   COURSE INFORMATION & PRICING

   Youth = Ages 10–16
   Adult = Ages 17+
========================================================= */

const academyCourses = {

  "cornrows": {

    id: "cornrows",

    name: "Cornrows Fundamentals",

    category: "Braiding",

    youthPrice: 199,

    adultPrice: 249,

    duration: "4.5 Hours",

    description:
      "Learn clean parting, proper hand placement, braid control, tension, cornrow technique and professional finishing.",

    image:
      "images/Class Images/cornrows-class.png"

  },


  "feed-in-braids": {

    id: "feed-in-braids",

    name: "Feed In Braids",

    category: "Braiding",

    youthPrice: 225,

    adultPrice: 275,

    duration: "4.5 Hours",

    description:
      "Learn clean parting, proper feed in placement, adding extension hair, braid consistency, tension control and finishing techniques.",

    image:
      "images/Class Images/feed-in-braids-class.png"

  },


  "stitch-braids": {

    id: "stitch-braids",

    name: "Stitch Braids",

    category: "Braiding",

    youthPrice: 249,

    adultPrice: 299,

    duration: "4.5 Hours",

    description:
      "Learn precision parting, feed in placement, stitch creation, braid consistency, product placement and professional finishing techniques.",

    image:
      "images/Class Images/stitch-braids-class.png"

  },


  "box-braids": {

    id: "box-braids",

    name: "Box Braids",

    category: "Braiding",

    youthPrice: 225,

    adultPrice: 275,

    duration: "4.5 Hours",

    description:
      "Learn clean box parting, extension hair placement, braid control, consistent sizing, tension and professional finishing.",

    image:
      "images/Class Images/box-braids-class.png"

  },


  "quick-weave": {

    id: "quick-weave",

    name: "Quick Weave",

    category: "Hair Installation",

    youthPrice: 249,

    adultPrice: 299,

    duration: "4.5 Hours",

    description:
      "Learn proper preparation, protective techniques, track placement, cutting, blending and professional quick weave installation.",

    image:
      "images/Class Images/quick-weave-class.png"

  },


  "sew-in": {

    id: "sew-in",

    name: "Sew In Installation",

    category: "Hair Installation",

    youthPrice: 275,

    adultPrice: 325,

    duration: "4.5 Hours",

    description:
      "Learn foundation braiding, proper track placement, sewing techniques, blending, securing wefts and professional finishing.",

    image:
      "images/Class Images/sew-in-class.png"

  }

};


/* =========================================================
   MONTHLY CLASS SCHEDULE

   Big Boss Beauty Academy classes are normally held:
   LAST SATURDAY OF EACH MONTH
   12:00 PM - 4:30 PM

   IMPORTANT:
   seatsRemaining controls what customers see.

   7 = 7 Seats Available
   3 = 3 Seats Remaining
   1 = 1 Seat Remaining
   0 = SOLD OUT / JOIN WAITING LIST

   STATUS OPTIONS:

   "open"
   "sold-out"
   "closed"
   "coming-soon"
========================================================= */

const classSchedule = [

  {
    id: "september-2026",
    courseId: "stitch-braids",
    date: "September 26, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },


  {
    id: "october-2026",
    courseId: "quick-weave",
    date: "October 31, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },


  {
    id: "november-2026",
    courseId: "sew-in",
    date: "November 28, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },


  {
    id: "december-2026",
    courseId: "box-braids",
    date: "December 26, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },


  {
    id: "january-2027",
    courseId: "cornrows",
    date: "January 30, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },


  {
    id: "february-2027",
    courseId: "feed-in-braids",
    date: "February 27, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  }

];


/* =========================================================
   HELPER FUNCTION
   GET COURSE INFORMATION
========================================================= */

function getAcademyCourse(courseId){

  return academyCourses[courseId] || null;

}


/* =========================================================
   HELPER FUNCTION
   GET PRICE BASED ON STUDENT TYPE
========================================================= */

function getAcademyPrice(courseId, studentType){

  const course =
    getAcademyCourse(courseId);

  if(!course){
    return 0;
  }

  if(studentType === "youth"){
    return course.youthPrice;
  }

  if(studentType === "adult"){
    return course.adultPrice;
  }

  return 0;

}


/* =========================================================
   HELPER FUNCTION
   DETERMINE STUDENT TYPE FROM AGE
========================================================= */

function getStudentTypeFromAge(age){

  const studentAge =
    Number(age);

  if(
    studentAge >= 10 &&
    studentAge <= 16
  ){
    return "youth";
  }

  if(studentAge >= 17){
    return "adult";
  }

  return "ineligible";

}


/* =========================================================
   HELPER FUNCTION
   SEAT DISPLAY
========================================================= */

function getSeatMessage(classSession){

  if(
    classSession.status === "sold-out" ||
    classSession.seatsRemaining <= 0
  ){
    return "SOLD OUT";
  }

  if(classSession.status === "closed"){
    return "REGISTRATION CLOSED";
  }

  if(classSession.status === "coming-soon"){
    return "REGISTRATION COMING SOON";
  }

  if(classSession.seatsRemaining === 1){
    return "ONLY 1 SEAT REMAINING";
  }

  if(classSession.seatsRemaining <= 3){
    return "ONLY " +
      classSession.seatsRemaining +
      " SEATS REMAINING";
  }

  return classSession.seatsRemaining +
    " SEATS AVAILABLE";

}


/* =========================================================
   HELPER FUNCTION
   REGISTRATION BUTTON TEXT
========================================================= */

function getRegistrationButtonText(classSession){

  if(
    classSession.status === "sold-out" ||
    classSession.seatsRemaining <= 0
  ){

    if(academySettings.waitingListEnabled){
      return "JOIN WAITING LIST";
    }

    return "SOLD OUT";
  }

  if(classSession.status === "closed"){
    return "REGISTRATION CLOSED";
  }

  if(classSession.status === "coming-soon"){
    return "COMING SOON";
  }

  return "REGISTER NOW";

}


/* =========================================================
   WAITING LIST CHECK
========================================================= */

function shouldShowWaitingList(classSession){

  return (
    academySettings.waitingListEnabled === true &&
    (
      classSession.status === "sold-out" ||
      classSession.seatsRemaining <= 0
    )
  );

}


/* =========================================================
   CLASS BENEFITS
========================================================= */

const academyClassBenefits = [

  "4.5 Hours of Hands On Instruction",

  "Small Class — Maximum 7 Students",

  "Mannequin Head Included",

  "Class Supplies Included",

  "Practice Hair and Materials Included",

  "Step by Step Instruction",

  "Certificate of Completion",

  "Take Home Practice Materials"

];


/* =========================================================
   AGE GROUP INFORMATION
========================================================= */

const academyAgeGroups = {

  youth: {
    label: "Youth Class",
    ages: "Ages 10–16"
  },

  adult: {
    label: "Adult Class",
    ages: "Ages 17+"
  }

};


/* =========================================================
   END BIG BOSS BEAUTY ACADEMY SCHEDULE
========================================================= */
