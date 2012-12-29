<?php
namespace QC\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

use QC\AppBundle\Entity\Customer;
use QC\AppBundle\Entity\CustomerContact;
use QC\AppBundle\Entity\Job;
use QC\AppBundle\Entity\JobItem;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('qc:import')
            ->setDescription('Import data from the Expert Systems MDB')
            ->addArgument(
            'path',
            InputArgument::REQUIRED,
            'Specify the path of the MDB'
        )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        $command = $this->getApplication()->find('doctrine:schema:drop');
        if($command->run(new ArrayInput(array('--force'=>true, '')), $output) == 0) {
            $output->writeln('Schema successfully dropped');
        }

        $command = $this->getApplication()->find('doctrine:schema:create');
        if($command->run(new ArrayInput(array('')), $output) == 0) {
            $output->writeln('Schema successfully created');
        }

        if(file_exists($path)){
            try{
                $connection = new \COM("ADODB.Connection")  or die("Unable to instantiate ADODB driver.");
                $connection->Open("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . $path . ";");
                $resultSet = $connection->Execute("
                SELECT
                    [ID],
                    [Company Name],
                    [Address1],
                    [Address2],
                    [Address3],
                    [Address4],
                    [Post Code],
                    [Note]
                FROM [Customers]");

                /** @var $entityManager \Doctrine\ORM\EntityManager **/
                $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();


                while (!$resultSet->EOF) {
                    $customerModel = new Customer();
                    $customerModel
                        ->setShortCode(utf8_encode($resultSet->Fields(0)->value))
                        ->setName(utf8_encode($resultSet->Fields(1)->value))
                        ->setAddress1(utf8_encode($resultSet->Fields(2)->value))
                        ->setAddress2(utf8_encode($resultSet->Fields(3)->value))
                        ->setAddress3(utf8_encode($resultSet->Fields(4)->value))
                        ->setAddress4(utf8_encode($resultSet->Fields(5)->value))
                        ->setPostcode(utf8_encode($resultSet->Fields(6)->value))
                        ->setNote(utf8_encode($resultSet->Fields(7)->value));

                    $entityManager->persist($customerModel);
                    $resultSet->MoveNext();
                }
                $entityManager->flush();

                $resultSet = $connection->Execute("
                SELECT
                    [ID],
                    [Contact Name],
                    [Position],
                    [Tel],
                    [Fax],
                    [Email],
                    [Mobile]
                FROM [Customer Contacts]");

                while (!$resultSet->EOF) {
                    $customerContactModel = new CustomerContact();
                    $customerContactModel
                        ->setName(utf8_encode($resultSet->Fields(1)->value))
                        ->setPosition(utf8_encode($resultSet->Fields(2)->value))
                        ->setTelephone(utf8_encode($resultSet->Fields(3)->value))
                        ->setFax(utf8_encode($resultSet->Fields(4)->value))
                        ->setEmail(utf8_encode($resultSet->Fields(5)->value))
                        ->setMobile(utf8_encode($resultSet->Fields(6)->value));

                    $customer = $entityManager->getRepository('QCAppBundle:Customer')->findOneByShortCode($resultSet->Fields(0)->value);
                    $customerContactModel->setCustomer($customer);
                    $entityManager->persist($customerContactModel);
                    $resultSet->MoveNext();
                }

                $resultSet = $connection->Execute("
                SELECT
                    [Job No],
                    [Company],
                    [Date],
                    [Date Required],
                    [Order No],
                    [Drg No],
                    [Description],
                    [Notes],
                    [Author],
                    [Status],
                    [Complete]
                FROM [Job File]");


                /** @var $jobItemModels JobItem[] */
                $jobItemModels = [];
                while (!$resultSet->EOF) {
                    $jobModel = new Job();

                    $dateCreated = date_create_from_format('d/m/Y H:i:s', utf8_encode($resultSet->Fields(2)->value));
                    if(!$dateCreated){
                        $dateCreated = date_create_from_format('d/m/Y', utf8_encode($resultSet->Fields(2)->value));
                    }
                    //echo $resultSet->Fields(3)->value;
                    $dateRequired = date_create_from_format('d/m/Y H:i:s', utf8_encode($resultSet->Fields(3)->value));
                    if(!$dateRequired){
                        $dateRequired = date_create_from_format('d/m/Y', utf8_encode($resultSet->Fields(3)->value));
                    }
                    if(!$dateRequired) $dateRequired = null;
                    if(!$dateCreated) $dateCreated = null;

                    $jobModel->setId(intval($resultSet->Fields(0)->value));
                    $description = ''.utf8_encode($resultSet->Fields(6)->value);

                    $matches = [];

                    if(preg_match_all('/[ ]*(\d)+[- ]*(off|)(.*)/i', $description, $matches)){
                        foreach($matches[0] as $i=>$line){
                            $jobItemModel = new JobItem();
                            $jobItemModel
                                ->setQuantity(intval($matches[1][$i]))
                                ->setDescription(trim($matches[3][$i]))
                            ;
                            $jobModel->addItem($jobItemModel);
                            $jobItemModels[] = $jobItemModel;
                            $description = str_replace($line, '', $description);
                        }
                    }

                    $jobModel
                        ->setDateCreated($dateCreated)
                        ->setDateRequired($dateRequired)
                        ->setOrderReference(utf8_encode($resultSet->Fields(4)->value))
                        ->setDrawingNumber(utf8_encode($resultSet->Fields(5)->value))
                        ->setDescription(trim($description))
                        ->setNote(utf8_encode($resultSet->Fields(7)->value))
                        ->setCreatedBy(utf8_encode($resultSet->Fields(8)->value))
                        ->setStatus(utf8_encode($resultSet->Fields(9)->value))
                        ->setCompleted(utf8_encode($resultSet->Fields(10)->value)?true:false)
                    ;



                    $customer = $entityManager->getRepository('QCAppBundle:Customer')->findOneByName($resultSet->Fields(1)->value);
                    $jobModel->setCustomer($customer);
                    //$jobModel->setItems(new \Doctrine\Common\Collections\ArrayCollection($jobItemModels));
                    $entityManager->persist($jobModel);

                    $resultSet->MoveNext();
                }

                $metadata = $entityManager->getClassMetaData(get_class(new Job()));

                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());

                $entityManager->flush();
                $connection->Close();
            }
            catch(\Exception $e){
                $output->writeln("Error importing data: ".$e->getMessage());
            }
        }
        else{
            $output->writeln("File '$path' does not exist");
        }
    }
}