<?php
namespace Config;

abstract class AppRouterName
{
    const CreateTest = 'test.create';
    const CreateExam       = 'exam.create';
    const EnterPin       = 'exam.enterPin';
    const CreateQuestion = 'questions.create';
    const UpdateQuestion = 'questions.update';
    const CreateSource = 'question.source.create';
    const UpdateQuestionMark = 'exam.updateQuestionMark';
    const UpdateSectionMark = 'exam.updateSectionMark';
}