/* =========================================================
   BIG BOSS BEAUTY ACADEMY
   CLASS SCHEDULE & PRICING

   Edit this file whenever you want to:
   - Add or change a class date
   - Assign a course to a Saturday
   - Change prices
   - Change available seats
   - Mark a class sold out
   - Close registration

   STANDARD CLASS TIME:
   12:00 PM - 4:30 PM

   WIG INSTALLATION:
   12:00 PM - 4:00 PM

   MAXIMUM STUDENTS:
   7 per class

   SCHEDULING:
   One course per Saturday
========================================================= */


/* =========================================================
   ACADEMY SETTINGS
========================================================= */

const academySettings = {

  academyName: "Big Boss Beauty Academy",

  classTime: "12:00 PM – 4:30 PM",

  wigClassTime: "12:00 PM – 4:00 PM",

  duration: "4.5 Hours",

  wigDuration: "4 Hours",

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

    youthPrice: 249,

    adultPrice: 299,

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

    youthPrice: 275,

    adultPrice: 325,

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

    youthPrice: 299,

    adultPrice: 349,

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

    youthPrice: 275,

    adultPrice: 325,

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

    youthPrice: 299,

    adultPrice: 349,

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

    youthPrice: 325,

    adultPrice: 375,

    duration: "4.5 Hours",

    description:
      "Learn foundation braiding, proper track placement, sewing techniques, blending, securing wefts and professional finishing.",

    image:
      "images/Class Images/sew-in-class.png"

  },


  "wig-installation": {

    id: "wig-installation",

    name: "Wig Installation",

    category: "Hair Installation",

    youthPrice: 400,

    adultPrice: 450,

    duration: "4 Hours",

    description:
      "Learn wig preparation, foundation techniques, lace preparation, customization, proper placement, secure installation, lace melting, styling and professional finishing techniques.",

    image:
      "images/Class Images/wig-installation-class.png"

  }

};


/* =========================================================
   SATURDAY CLASS SCHEDULE

   Big Boss Beauty Academy classes are held on Saturdays.

   ONE COURSE PER SATURDAY

   Standard Classes:
   12:00 PM - 4:30 PM

   Wig Installation:
   12:00 PM - 4:00 PM

   MAXIMUM:
   7 students per class

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
    id: "2026-09-26-stitch-braids",
    courseId: "stitch-braids",
    date: "September 26, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-10-03-quick-weave",
    courseId: "quick-weave",
    date: "October 3, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-10-10-wig-installation",
    courseId: "wig-installation",
    date: "October 10, 2026",
    time: "12:00 PM – 4:00 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-10-17-sew-in",
    courseId: "sew-in",
    date: "October 17, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-10-24-feed-in-braids",
    courseId: "feed-in-braids",
    date: "October 24, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-10-31-box-braids",
    courseId: "box-braids",
    date: "October 31, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-11-07-cornrows",
    courseId: "cornrows",
    date: "November 7, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-11-14-stitch-braids",
    courseId: "stitch-braids",
    date: "November 14, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-11-21-quick-weave",
    courseId: "quick-weave",
    date: "November 21, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-11-28-wig-installation",
    courseId: "wig-installation",
    date: "November 28, 2026",
    time: "12:00 PM – 4:00 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-12-05-sew-in",
    courseId: "sew-in",
    date: "December 5, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-12-12-feed-in-braids",
    courseId: "feed-in-braids",
    date: "December 12, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2026-12-19-box-braids",
    courseId: "box-braids",
    date: "December 19, 2026",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  /*
     DECEMBER 26, 2026
     NO CLASS - HOLIDAY

     JANUARY 2, 2027
     NO CLASS - HOLIDAY
  */

  {
    id: "2027-01-09-cornrows",
    courseId: "cornrows",
    date: "January 9, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-01-16-stitch-braids",
    courseId: "stitch-braids",
    date: "January 16, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-01-23-quick-weave",
    courseId: "quick-weave",
    date: "January 23, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-01-30-wig-installation",
    courseId: "wig-installation",
    date: "January 30, 2027",
    time: "12:00 PM – 4:00 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-02-06-sew-in",
    courseId: "sew-in",
    date: "February 6, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-02-13-feed-in-braids",
    courseId: "feed-in-braids",
    date: "February 13, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-02-20-box-braids",
    courseId: "box-braids",
    date: "February 20, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-02-27-cornrows",
    courseId: "cornrows",
    date: "February 27, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-03-06-stitch-braids",
    courseId: "stitch-braids",
    date: "March 6, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-03-13-quick-weave",
    courseId: "quick-weave",
    date: "March 13, 2027",
    time: "12:00 PM – 4:30 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-03-20-wig-installation",
    courseId: "wig-installation",
    date: "March 20, 2027",
    time: "12:00 PM – 4:00 PM",
    seatsRemaining: 7,
    status: "open"
  },

  {
    id: "2027-03-27-sew-in",
    courseId: "sew-in",
    date: "March 27, 2027",
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
   GET ALL SCHEDULED DATES FOR A COURSE
========================================================= */

function getAcademyCourseDates(courseId){

  return classSchedule.filter(function(classSession){

    return classSession.courseId === courseId;

  });

}


/* =========================================================
   HELPER FUNCTION
   GET CLASS SESSION
========================================================= */

function getAcademyClassSession(classSessionId){

  return classSchedule.find(function(classSession){

    return classSession.id === classSessionId;

  }) || null;

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

  "Hands On Instruction",

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
